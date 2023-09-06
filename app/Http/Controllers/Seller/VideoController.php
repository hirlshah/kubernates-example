<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use MetaTag;

class VideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Application|Factory|View|Response
     */
    public function index(Request $request)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Trainings'));
        MetaTag::set('description', config('app.rankup.company_title').'Videos Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $user = Auth::User();
        $memberIds = User::getDownlineIds($user->id);
        array_unshift($memberIds, $user->root_id);
        array_unshift($memberIds, $user->id);
        $memberIds = array_unique(array_merge($memberIds,User::getUplineArray($user)));

        $categoryIds = DB::table('user_category')->whereRaw('user_id IN ('.getDownlinesStr(implode(',', array_filter($memberIds))).')')->pluck('category_id')->toArray();
       
        $videoCategories = Category::query()->whereIn('id', $categoryIds)->where(['model_type' => 'formation', 'parent_id' => '0'])->orderBy('sort_order','asc')->get();

        $parentCategories = Category::where('model_type','=','formation')->where('parent_id','=',0)->select('id','name')->get();

        $params = compact('parentCategories', 'videoCategories');
        foreach($videoCategories as $videoCategory) {

            $video = $videoCategory->categoryVideos->first();
            if(!empty($video)) {
                 return redirect()->route('seller.video-detail', [$video->id, 'category' => $videoCategory->id]);
            } else if ($videoCategory->subCategories) {
                foreach($videoCategory->subCategories as $subCategory) {
                    $video = $subCategory->subCategoryVideos->first();
                    if(!empty($video)) {
                    return redirect()->route('seller.video-detail', [$video->id, 'category' => $subCategory->id]);
                    }
                }
            }
        }
        
        return view('seller.video.index', $params);
    }

    /**
     * Video detail page
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function videoDetail($id, Request $request) 
    {
        MetaTag::set('title',config('app.rankup.company_title')." - ".__('Trainings'));
        MetaTag::set('description', config('app.rankup.company_title').'Videos Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $mainVideo = Video::findOrFail($id);
         
        if(isset($mainVideo->sub_category_id)) {
            $categoryId = $mainVideo->sub_category_id;
        } else if($mainVideo->category_id) {
            $categoryId = $mainVideo->category_id;
        }
        
        if(isset($request->category)) {
            $categoryId = $request->category;
        }
        
        $user = Auth::User();
        $memberIds = User::getDownlineIds($user->id);
        array_unshift($memberIds, $user->root_id);
        array_unshift($memberIds, $user->id);
        $memberIds = array_unique(array_merge($memberIds,User::getUplineArray($user)));

        $categoryIds = DB::table('user_category')->whereRaw('user_id IN ('.getDownlinesStr(implode(',', array_filter($memberIds))).')')->pluck('category_id')->toArray();
       
        $videoCategories = Category::query()->whereIn('id', $categoryIds)->where(['model_type' => 'formation', 'parent_id' => '0'])->orderBy('sort_order','asc')->get();

        $parentCategories = Category::where('model_type','=','formation')->where('parent_id','=',0)->select('id','name')->get();
        $category = Category::where('id', $categoryId)->first();

        if(empty($category)) {
            $category = Category::latest()->first();
        }

        $categoryId = $category->id;
        $videoQuery = Video::where(function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId)
                  ->orWhere('sub_category_id', $categoryId);
        });

        $emptyMsg = false;
        if ($request->has('search')) {
            $emptyMsg = true;
            $searchText = $request->input('search');
            $videoQuery = $videoQuery->where('title', 'LIKE', '%' . $searchText . '%');
        }

        if($request->order_type) {
            $orderType = $request->order_type;
            if($orderType == 'most_recent') {
                $videoQuery->latest();
            } else if($orderType == 'oldest') {
                $videoQuery->oldest();
            } else if($orderType == 'asc') {
                $videoQuery->orderBy('title', 'asc');
            } else if($orderType == 'desc') {
                $videoQuery->orderBy('title', 'desc');
            }
        } else {
            $videoQuery->OrderBy('order', 'asc');
        }

        $videos = $videoQuery->get();

        $params = compact('mainVideo', 'category', 'videos', 'videoCategories', 'emptyMsg', 'parentCategories');

        if ($request->ajax()) {
            return view('seller.video.sub_category_video_data', $params);
        }

        return view('seller.video.sub_category_videos', $params);
    }

    /**
     * Store video
     *
     * @param VideoRequest $request
     *
     * @return JsonResponse
     */
    public function store(VideoRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['video'] = $data['video_link'];
        $data['order'] = 1;
        $data['sub_category_id'] = $data['sub_category_id'] ?? 0;

        if (!empty($request->sort_array)) {
            $data['sort_array'] = json_decode($request->sort_array, true);
            $counter = 1;
            foreach ($request->sort_array as $videoId) {
                Video::where(['id' => $videoId])->update(['order' => $counter]);
                $counter++;
            }
        }

        $video = Video::create($data);

        if (isset($request->tags)) {
            $tags = Tag::findOrCreate(array_filter($request->tags));
            $video->tags()->sync($tags);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('videos.index'),
        ], 200);
    }

    /**
     * Update video
     *
     * @param VideoRequest $request
     * @param Video $video
     *
     * @return JsonResponse
     */
    public function update(VideoRequest $request, Video $video)
    {
        $data = request()->all();
        $data['sub_category_id'] = $request->sub_category_id ?? 0;
        $data['video'] = $request->video_link;
        $video->update($data);

        if (isset($request->tags)) {
            $tags = Tag::findOrCreate($request->tags);
            $video->tags()->sync($tags);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('videos.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Video $video
     *
     * @return JsonResponse
     */
    public function show(Video $video)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'title' => $video->title,
                'description' => $video->description,
                'tags' => $video->tags->pluck('name')->toArray(),
                'video' => $video->video,
                'category' => $video->category_id,
                'sub_category_id' => $video->sub_category_id
            ],
        ], 200);
    }

    /**
     * Delete video
     *
     * @param Video $video
     *
     * @return Response
     */
    public function destroy(Video $video)
    {
        $categoryId = $video->category_id;
        if($video->delete()){
            $redirectVideo = Video::where('category_id', $categoryId)->first();
            if(empty($redirectVideo)) {
                $redirectVideo = Video::whereNotNull('category_id')->first();
            }
            $redirectUrl = route('seller.video-detail', ['id' => $redirectVideo->id, 'category' => $redirectVideo->category_id]);
            
            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ], 200);        
        } else  {
            return response()->json([
                'success' => false,
            ], 200);
        }
    }

    /**
     * Drag drop video
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function dragDropVideo(Request $request)
    {
        if ($request->sort_array) {
            $counter = 1;
            foreach ($request->sort_array as $videoId) {
                Video::where(['id' => $videoId])->update(['order' => $counter]);
                $counter++;
            }
        }

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * @param Request $request
     *
     *  @return JsonResponse
     */
    function dragDropCategory(Request $request) {
        if ($request->sort_array) {
            $counter = 1;
            foreach ($request->sort_array as $categoryId) {
                $i = Category::where(['id' => $categoryId])->update(['sort_order' => $counter]);
                $counter++;
            }
        }

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Download video - not used for now
     *
     * @param Video $video
     *
     * @return RedirectResponse
     */
    public function downloadVideo(Video $video)
    {
        if (!$video->video) {
            return Redirect::back()->with('success', __('Invalid Video Name'));
        }

        if (Storage::disk('public')->missing($video->video)) {
            Session::flash('success', __('Video not found'));
            return Redirect::back();
        }

        return Storage::disk('public')->download($video->video);
    }

    /**
     * Add the entry to the user_video table
     * 
     * @param Video $video
     * 
     * @return JsonResponce
     */
    public function addVideoCompleted(Video $video) {
        $user = Auth::user();
        $user->videos()->sync([$video->id]);
        return response()->json([
            'success' => true,
        ], 200);
    }
}
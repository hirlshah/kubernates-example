<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
use App\Models\Document;
use App\Models\ProspectionVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\DB;
use App\Classes\Helper\CommonUtil;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     *
     * @return JsonResponse
     */
    public function store(CategoryRequest $request) 
    {
	    $user = Auth::User();
    	if(isset($request->name) && !empty($request->name)) {
            $category = Category::updateOrCreate(
                ['name' => $request->name],
                ['model_type' => $request->model_type, 'name' => $request->name]
            );
            if(isset($category)) {
                $category->users()->sync($user->id);
            }
	    }

    	return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Store a newly subcategory.
     *
     * @param SubCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function subCategoryStore(SubCategoryRequest $request) 
    {
        $user = Auth::User();
        $data = $request->all();

        $category = [
            'model_type' => $data['model_type'],
            'name' => $data['name'],
            'parent_id' => $data['parent_id']
        ];
        $category = Category::create($category);
        if(isset($category)) {
            $category->users()->sync($user->id);
        }

    	return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Show category
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id) 
    {
        $category = Category::findOrFail($id);
        $data = [
            'name' => $category->name,
            'model_type' => $category->model_type,
            'parent_id' => $category->parent_id
        ];
        return response()->json($data);
    }

    /**
     * Show a newly subcategory.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function subCategoryShow(Request $request) 
    {
        $subCategory = Category::where('parent_id','=',$request->category_id)->pluck('id','name');
        return response()->json($subCategory);
    }

    /**
     * Delete Category
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id) 
    {
        Video::where(['category_id' => $id])->delete();
        $documents = Document::where(['category_id' => $id])->get();
        foreach($documents as $document) {
            if(isset($document->document)) {
                CommonUtil::removeFile($document->document);
            }
            if(isset($document->image)) {
                CommonUtil::removeFile($document->image);
            }
            $document->delete();
        }

        $prospectionVideos = ProspectionVideo::where(['category_id' => $id])->get();
        foreach($prospectionVideos as $prospectionVideo) {
            if (isset($prospectionVideo->video)) {
                CommonUtil::removeFile($prospectionVideo->video);
            }
            if (isset($prospectionVideo->video_cover_image)) {
                CommonUtil::removeFile($prospectionVideo->video_cover_image);
            }
            $prospectionVideo->delete();
        }

        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'data' => 'category'
        ], 200);
    }

    /**
     * Delete sub category
     *
     * @param string $type
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroySubCategory($type, $id) 
    {
        if($type == 'video') {
            Video::where('sub_category_id',$id)->update(['sub_category_id' => 0]);
        } elseif($type == 'document') {
            Document::where('sub_category_id',$id)->update(['sub_category_id' => 0]);
        } elseif($type == 'prospectionVideo') {
            ProspectionVideo::where('sub_category_id',$id)->update(['sub_category_id' => 0]);
        }
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'data' => 'category'
        ], 200);
    }

    /**
     * Update category
     *
     * @param CategoryRequest $request
     *
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, $id) 
    {
        $user = Auth::User();
        $category = Category::findOrFail($id);
        if(!empty($category)) {
            $data = $request->all();

            $data = [
                'model_type' => $data['model_type'],
                'name' => $data['name'],
            ];
            $category->update($data);
            $category->users()->sync($user->id);

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 200);
        }
    }

    /**
     * Update sub category
     *
     * @param SubCategoryRequest $request
     *
     * @return JsonResponse
     */
    public function updateSubCategory(SubCategoryRequest $request, $id) 
    {
        $user = Auth::User();
        $category = Category::findOrFail($id);

        if(!empty($category)) {
            $data = $request->all();

            Video::where('sub_category_id', $category->id)->update(['category_id' => $data['parent_id']]);

            $data = [
                'model_type' => $data['model_type'],
                'name' => $data['name'],
                'parent_id' => $data['parent_id']
            ];
            $category->update($data);

            $category->users()->sync($user->id);

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 200);
        }
    }
}
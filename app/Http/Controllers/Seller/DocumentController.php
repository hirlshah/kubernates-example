<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\ModuleConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use MetaTag;
use Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware(['auth', 'verified']);

        $recordExist = ModuleConfig::checkForModuleNotExist('Documents');

        if($recordExist) {
            abort(404);
        }

        MetaTag::set('title', config('app.rankup.company_title').' - Documents');
        MetaTag::set('description', config('app.rankup.company_title').' Documents Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) 
    {
        $user = Auth::User();
        $memberIds = User::getDownlineIds($user->id);
        array_unshift($memberIds, $user->root_id);
        array_unshift($memberIds, $user->id);

        $memberIds = array_filter(array_unique(array_merge($memberIds, User::getUplineArray($user))));
        $memberIds = getDownlinesStr(implode(',', $memberIds));
        $categoryIds = DB::table('user_category')->whereRaw('user_id IN ('.$memberIds.')')->pluck('category_id')->toArray();
        $categories = Category::query()->whereIn('id', $categoryIds)->where(['model_type' => 'document', 'parent_id' => '0'])->pluck('name','id');
        $categories->prepend(__('All contents'), 0);
        $authCategoryIds = DB::table('user_category')->where(['user_id' => Auth::User()->id])->pluck('category_id')->toArray();

        $filteredCategory = $filteredSubCategory = 0;
        if($request->ajax()){
            $searchText = $request->search ?? '';
            $filteredCategory = $request->category_filter??Session::get('document.index.category_filter', 0);
            $filteredSubCategory = $request->sub_category_filter??Session::get('document.index.sub_category_filter', 0);
        }

        $parentCategoryFilter = '';
        if(isset($request->category_filter) && ($request->category_filter != 0) ){
            $parentCategoryFilter = $request->category_filter;
        }

        if(!empty($filteredSubCategory)) {
            $subCategory = Category::where(['id' => $filteredSubCategory])->first();
            if(!empty($subCategory)){
                $parentCategoryFilter = $subCategory->parent_id;
            }
        }

        $subNewCategories = [];
        if( !empty($parentCategoryFilter) ){
            $subNewCategories = Category::where('model_type','=','document')->where('parent_id','=',$parentCategoryFilter)->get();
        }

        $documentQuery = Document::orderBy('created_at', 'desc');

        if(intval($filteredCategory)){
            $documentQuery->whereHas('category', function ($q) use ($filteredCategory){
                $q->where('category_id', $filteredCategory);
            });
        }

        if(intval($filteredSubCategory)){
            $documentQuery->whereHas('category', function ($q) use ($filteredSubCategory){
                $q->where('sub_category_id', $filteredSubCategory);
            });
        }

        if(isset($searchText) && !empty($searchText)){
            $documentQuery->where(function($query) use ($searchText) {
                $query->where('title','LIKE','%'.$searchText.'%')
                    ->orWhereHas('category', function ($q) use ($searchText) {
                        $q->where('name',  'like', '%'.$searchText.'%');
                    })
                    ->orWhereHas('user', function ($q) use ($searchText) {
                        $q->where('name',  'like', '%'.$searchText.'%');
                    });
            });
        }

        $documentQuery->whereRaw('user_id IN ('.$memberIds.')');
        $documents = $documentQuery->paginate(12);
        $documentCount = $documents->total();

        Session::put('document.index.category_filter', $filteredCategory);

        $parentCategories = Category::where('model_type','=','document')->where('parent_id','=',0)->select('id','name')->get();

        $params = compact('documents', 'categories','filteredCategory','documentCount', 'authCategoryIds', 'parentCategories', 'subNewCategories', 'filteredSubCategory');
        if ($request->ajax()) {
            return view('seller.documents._document_pagination', $params);
        }

        return view('seller.documents.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() 
    {
        $tags = Tag::pluck('name', 'id');
        return view('seller.documents.create_update', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DocumentRequest $request
     * @param Document $document
     *
     * @return Response
     */
    public function store(DocumentRequest $request, Document $document) 
    {
        $data = request()->all();
        $data['user_id'] = Auth::id();
        $data['sub_category_id'] = $request->sub_category_id ?? 0;
        if( $request->hasFile('document') ) {
            $documentName = CommonUtil::uploadFileToFolder($request->file('document'), 'documents');
            $data['document'] = $documentName;
        }

        if( $request->hasFile('image') ) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'documents/image');
            $data['image'] = $imageName;
        }

	    if($document = Document::create($data)) {
            if(isset($request->tags)) {
                $tags = Tag::findOrCreate(array_filter($request->tags));
                $document->tags()->sync($tags);
            }
            return response()->json([
                'success' => true,
                'redirect_url' => ''
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Document $document
     *
     * @return Response
     */
    public function show(Document $document) 
    {
        return view('seller.documents.show',compact('document'));
    }

    /**
     * Display a document edit form.
     *
     * @param Document $document
     *
     * @return Response
     */
    public function edit(Document $document) 
    {
        $tags = Tag::pluck('name', 'id');
        $tagIds = $document->tags()->get()->pluck('name')->toArray();
        return response()->json([
            'data' => $document,
            'tags'=>$tagIds
        ], 200);
        return view('seller.documents.create_update',compact('document', 'tags','tagIds'));
    }

    /**
     * Update Document
     *
     * @param DocumentRequest $request
     * @param Document $document
     *
     * @return Response
     */
    public function update(DocumentRequest $request, Document $document) 
    {
        $data = $request->all();
        $data['sub_category_id'] = $request->sub_category_id ?? 0;
        $removeDocument = false;
        $removeImage = false;
        if( $request->hasFile('document') ) {
            $documentName = CommonUtil::uploadFileToFolder($request->file('document'), 'documents');
            $data['document'] = $documentName;
            $removeDocument = true;
        }
        if($removeDocument && !empty($document->document)) {
            CommonUtil::removeFile($document->document);
        }

        if( $request->hasFile('image') ) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'documents/image');
            $data['image'] = $imageName;
            $removeImage = true;
        }
        if($removeImage && !empty($document->image)) {
            CommonUtil::removeFile($document->image);
        }

        if ( $document->update( $data ) ) {
            if(isset($request->tags)) {
                $tags = Tag::findOrCreate($request->tags);
                $document->tags()->sync($tags);
            }
            return response()->json([
                'success' => true,
                'redirect_url' => ''
            ], 200);
        }
    }

    /**
     * Delete Document
     *
     * @param Document $document
     *
     * @return Response
     */
    public function destroy(Document $document) 
    {
        if(isset($document->document)) {
            CommonUtil::removeFile($document->document);
        }
        if(isset($document->image)) {
            CommonUtil::removeFile($document->image);
        }
        $document->delete();
    }

    /**
     * Download document
     *
     * @param Document $document
     *
     * @return RedirectResponse|StreamedResponse
     */
    public function downloadDocument(Document $document) 
    {
        if (! $document->document) {
            return Redirect::back()->with('success', __('Invalid Document Name'));
        }

        if(Storage::disk('public')->missing($document->document)){
            Session::flash( 'success', 'Document not found');
            return Redirect::back();
        }

        return Storage::disk('public')->download($document->document);
    }
}
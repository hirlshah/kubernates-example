<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Support\Facades\Session;
use MetaTag;
use Yajra\Datatables\Datatables;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->middleware( 'auth' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() 
    {
        MetaTag::set('title', config('app.rankup.company_title').' - Pages');
        MetaTag::set('description', config('app.rankup.company_title').' Pages Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('admin.pages.index');
    }

    /**
     * Get all Pages
     */
    public function getData() 
    {
        return Datatables::of(Page::select('id', 'page_id'))
            ->addColumn( 'action', function ( $data ){
            return
                '<a href="javascript:;" data-url="' . url( '/admin/pages/' . $data->id) . '" class="modal-popup-view btn control-action legitRipple"><i class="fa fa-eye"></i></a>' .
                '<a href="' . url( '/admin/pages/' . $data->id . '/edit' ) . '" class="btn btn-edit"><i class="feather-edit"></i></a>' ;
            })
        ->rawColumns(['action'])
        ->make( true );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Add Page'));
        MetaTag::set('description', config('app.rankup.company_title').' Add Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('admin.pages.create_update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     *
     * @return Response
     */
    public function store(PageRequest $request) 
    {
        $data = $request->all();
        request()->validate(
            array_merge(
                array(
                    'page_id' => 'required',
                )
            )
        );
        if ( Page::create( $data ) ) {
            Session::flash('success', __('Page has been added!'));
            return redirect()->back();
        } else {
            Session::flash('error', __('Unable to create page.'));
            return redirect(route('pages.create'))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     *
     * @return Response
     */
    public function show( Page $page ) 
    {
        $data  = [
            'ID'            =>  $page->id,
            'Page Id'       =>  $page->page_id,
            'Title'         =>  $page->title,
            'Description'   =>  $page->description,
        ];

        return $data;
    }

    /**
     * Display a event edit form.
     *
     * @param Page $page
     *
     * @return Response
     */
    public function edit( Page $page ) 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Edit Page'));
        MetaTag::set('description', config('app.rankup.company_title').' Edit Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('admin.pages.create_update',compact('page'));
    }

    /**
     * Update Event
     *
     * @param PageRequest $request
     * @param Page $page
     *
     * @return Response
     */
    public function update( PageRequest $request, Page $page ) 
    {
        $data = $request->all();
        if ( $page->update( $data ) ) {
            Session::flash('success', __('Page has been updated!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to update page.'));
        }
    }   
}
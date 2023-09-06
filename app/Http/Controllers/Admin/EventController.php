<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Helper\CommonUtil;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\Tag;
use Auth;
use File;
use Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Session;
use Yajra\Datatables\Datatables;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct() 
    {
        $this->middleware('permission:event-list|event-create|event-edit|event-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:event-create', ['only' => ['create','store']]);
        $this->middleware('permission:event-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:event-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() 
    {
        $eventCount = Event::count();
        return view('admin.events.index', compact('eventCount'));
    }

    /**
     * Get all Events for listing
     */
    public function getData() 
    {
        return Datatables::of(Event::query())
            ->addColumn('image', function ($data){
                if($data->image == null || Storage::disk('public')->missing($data->image)){
                    $url = asset('uploads/static.png');
                } else {
                    $url = CommonUtil::getUrl($data->image);
                }
                return '<img src="' . $url . '" border="0" width="100" height="100" class="img-rounded" align="center" />';
            })->addColumn( 'action', function ( $data ){
                return
                    '<a href="' . url( '/admin/events/' . $data->id) . '" class="btn btn-view"><i class="feather-eye"></i></a>' .
                    '<a href="' . url( '/admin/events/' . $data->id . '/edit' ) . '" class="btn btn-edit"><i class="feather-edit"></i></a>' .
                    '<a href="javascript:;" data-url="' . url( '/admin/events/' . $data->id ) . '" class="modal-popup-delete btn btn-delete"><i class="feather-trash-2"></i></a>';
            })
            ->rawColumns(['image','action'])
            ->make( true );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() 
    {
        $tags = Tag::pluck('name', 'id');
        return view('admin.events.create_update', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventRequest $request
     * @param Event $event
     *
     * @return Response
     */
    public function store(EventRequest $request,Event $event) 
    {
        request()->validate(
            array_merge(
                array(
                    'image' => 'required|mimes:jpg,jpeg,png,svg|max:15000',
                )
            )
        );

        $data = request()->all();
        if($request->content_message) {
            $data['content'] = $request->content_message;
        }
        $slug = Str::slug($data['name']);
        $data['user_id'] = Auth::User()->id;
        $data['slug'] = $slug;
        if( $request->hasFile('image') ) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'events');
            $data['image'] = $imageName;
        }
        if ( $event = Event::create( $data ) ) {
            $event->tags()->sync($request->tags);
            Session::flash('success', __('Event has been added!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to create event.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     *
     * @return Response
     */
    public function show(Event $event) 
    {
        return view('admin.events.show',compact('event'));
    }

    /**
     * Display a event edit form.
     *
     * @param Event $event
     *
     * @return Response
     */
    public function edit(Event $event) 
    {
        $tags = Tag::pluck('name', 'id');
        $tagIds = $event->tags()->get()->pluck('id')->toArray();
        return view('admin.events.create_update',compact('event', 'tags','tagIds'));
    }

    /**
     * Update Event
     *
     * @param EventRequest $request
     * @param Event $event
     *
     * @return Response
     */
    public function update(EventRequest $request, Event $event) 
    {
        $data = $request->all();
        if( $request->hasFile('image') ) {
            if(isset($event->image)) {
                CommonUtil::removeFile($event->image);
            }
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'events');
            $data['image'] = $imageName;
        } else {
            $data['image'] = $event->image;
        }

        if($request->content_message) {
            $data['content'] = $request->content_message;
        }

        if ( $event->update( $data ) ) {
            $event->tags()->sync($request->tags);
            Session::flash('success', __('Event has been updated!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to update event.'));
        }
    }

    /**
     * Delete Event
     *
     * @param Event $event
     *
     * @return Response
     */
    public function destroy(Event $event) 
    {
        $event->delete();
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\HelpRequest;
use App\Models\Help;
use Session;
use MetaTag;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class HelpController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Helps'));
        MetaTag::set('description', config('app.rankup.company_title').' Helps Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $helpCount = Help::count();
        return view('admin.helps.index', compact('helpCount'));
    }

     /**
     * Get all Help for listing
     */
    public function getData()
    {
        return Datatables::of(Help::select('id', 'title_en', 'title_fr', 'url'))->addColumn('action', function ($data) {
            return
            '<a href="javascript:;" data-url="' . url('/admin/helps/' . $data->id. '/show') . '" class="modal-popup-view btn control-action legitRipple py-0 px-0 shadow-none"><i class="fa fa-eye"></i></a>' .
            '<a href="' . url('/admin/helps/' . $data->id . '/edit') . '" class="btn btn-edit px-2 py-0 shadow-none"><i class="feather-edit"></i></a>' .
            '<a href="javascript:;" data-url="' . url('/admin/helps/' . $data->id) . '" class="modal-popup-delete btn btn-delete py-0 px-0 shadow-none"><i class="feather-trash-2"></i></a>';
        })->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Add Help'));
        MetaTag::set('description', config('app.rankup.company_title').' Add Help Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));

        return view('admin.helps.create_update');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param HelpRequest $request
     *
     * @return Response
     */
    public function store(HelpRequest $request)
    {
        $data = request()->all();

        if ($help = Help::create($data)) {
            Session::flash('success', __('Help has been added!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to create help.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Help $help
     * @return Response
     */
    public function show(Help $help)
    {
        $data = [
            'Title En' => $help->title_en,
            'Title Fr' => $help->title_fr,
            'Url' => $help->url,
        ];

        return $data;
    }

    /**
     * Display a help edit form.
     *
     * @param Help $help
     *
     * @return Response
     */
    public function edit(Help $help)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Edit Help'));
        MetaTag::set('description', config('app.rankup.company_title').' Edit User Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $helpsLists = Help::all();
        foreach ($helpsLists as $helpLists) {
            $helps[$helpLists->id] = $helpLists->title_fr;
        }
        return view('admin.helps.create_update', compact('help', 'helps'));
    }

        /**
     * Update User
     *
     * @param Help $help
     * @param HelpRequest $request
     *
     * @return Response
     */
    public function update(HelpRequest $request, Help $help)
    {
        $data = $request->all();

        if ($help->update($data)) {
            Session::flash('success', __('Help has been updated!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to update help.'));
        }
    }

    /**
     * Delete Help
     *
     * @param Help $help
     *
     * @return Response
     */
    public function destroy(Help $help)
    {
        $help = Help::where('id','=',$help->id)->delete();
    }
}
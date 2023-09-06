<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct() 
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() 
    {
	    $roleCount = Role::count();
        return view('admin.roles.index', compact('roleCount'));
    }

    /**
     * Get all Role for listing
     */
    public function getData() 
    {
        return Datatables::of(Role::query())
            ->addColumn( 'action', function ( $data ){
                return
                    '<a href="' . url( '/admin/roles/' . $data->id) . '" class="btn btn-view"><i class="feather-eye"></i></a>' .
                    '<a href="' . url( '/admin/roles/' . $data->id . '/edit' ) . '" class="btn btn-edit"><i class="feather-edit"></i></a>' .
                    '<a href="javascript:;" data-url="' . url( '/admin/roles/' . $data->id ) . '" class="modal-popup-delete btn btn-delete"><i class="feather-trash-2"></i></a>';
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
        $permission = Permission::get();
        return view('admin.roles.create_update',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     *
     * @return Response
     */
    public function store(RoleRequest $request,Role $role) 
    {
        $data = request()->all();
        request()->validate(
            array_merge(
                array(
                    'name' => 'required|unique:roles,name',
                )
            )
        );
        if ( $role = Role::create( $data ) ) {
            $role->syncPermissions($request->input('permission'));
            Session::flash('success', __('Role has been added!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to create role.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return Response
     */
    public function show( Role $role ) 
    {
	    $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
	        ->where("role_has_permissions.role_id",$role->id)
	        ->get();
        return view('admin.roles.show',compact('role','rolePermissions'));
    }

    /**
     * Display a role edit form.
     *
     * @param Role $role
     *
     * @return Response
     */
    public function edit(Role $role) 
    {
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)
                             ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                             ->all();
        return view('admin.roles.create_update',compact('role','permission','rolePermissions'));
    }

    /**
     * Update Role
     *
     * @param RoleRequest $request
     * @param Role $role
     *
     * @return Response
     */
    public function update( RoleRequest $request, Role $role ) 
    {
        $data = $request->all();
        request()->validate(
            array_merge(
                array(
                    'name' => 'required|unique:roles,name,'.$role->id,
                )
            )
        );
        if ( $role->update( $data ) ) {
            $role->syncPermissions($request->input('permission'));
            Session::flash('success', __('Role has been updated!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to update role.'));
        }
    }

    /**
     * Delete Role
     *
     * @param Role $role
     *
     * @return Response
     */
    public function destroy(Role $role) 
    {
        $role->delete();
    }
}
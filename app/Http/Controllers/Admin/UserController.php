<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Helper\CommonUtil;
use App\Classes\Helper\ReferralCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ImportRequest;
use App\Models\User;
use App\Models\UserPlan;
use File;
use Hash;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use MetaTag;
use Session;
use Spatie\Permission\Models\Role;
use Yajra\Datatables\Datatables;
use App\Enums\UserPlanStatus;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Users'));
        MetaTag::set('description', config('app.rankup.company_title').' Users Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $userCount = User::count();
        return view('admin.users.index', compact('userCount'));
    }

    /**
     * Get all User for listing
     */
    public function getData()
    {
        return Datatables::of(User::select('id', 'name', 'user_name', 'email', 'profile_image'))
            ->addColumn('profile_image', function ($data) {
                if ($data->profile_image == null || Storage::disk('public')->missing($data->profile_image)) {
                    $url = asset('uploads/static.png');
                } else {
                    $url = CommonUtil::getUrl($data->profile_image);
                }
                return '<img src="' . $url . '" border="0" width="100" height="100" class="img-rounded" align="center" />';
            })->addColumn('action', function ($data) {
            return
            '<a href="javascript:;" data-url="' . url('/admin/users/' . $data->id) . '" class="modal-popup-view btn control-action legitRipple py-0 px-0 shadow-none"><i class="fa fa-eye"></i></a>' .
            '<a href="' . url('/admin/users/' . $data->id . '/edit') . '" class="btn btn-edit px-2 py-0 shadow-none"><i class="feather-edit"></i></a>' .
            '<a href="javascript:;" data-url="' . url('/admin/users/' . $data->id) . '" class="modal-popup-delete btn btn-delete py-0 px-0 shadow-none"><i class="feather-trash-2"></i></a>';
        })
            ->rawColumns(['profile_image', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Add User'));
        MetaTag::set('description', config('app.rankup.company_title').' Add User Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create_update', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $data = request()->all();
        if (request()->has('password')) {
            $data['password'] = Hash::make($data['password']);
        }
        if ($request->hasFile('video')) {
            $videoName = CommonUtil::uploadFileToFolder($request->file('video'), 'users/video');
            $data['video'] = $videoName;
        }
        if ($request->hasFile('profile_image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('profile_image'), 'users/image');
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('profile_image'), 'users/thumbnails');
            $data['profile_image'] = $imageName;
            $data['thumbnail_image'] = $thumbanilImage;
        }
        if (!empty($data['parent_id'])) {
            $userDetail = User::where(['id' => $data['parent_id']])->first();
            if ($userDetail) {
                $emptyNodeId = User::findEmptyNode($userDetail->id);
                $data['parent_id'] = $emptyNodeId;
                $data['root_id'] = $userDetail->root_id ?? $userDetail->id;
            }
        }
        $data['email_verified_at'] = date('Y-m-d H:i:s');
        $referralCode = new ReferralCode();
        $data['referral_code'] = $referralCode->createReferralCode();
        if ($user = User::create($data)) {
            $user->assignRole($request->input('roles'));
            if(config('app.rankup.comapny_name') == "rankup"){
                $user->assignFreePlan();
            }
            
            Session::flash('success', __('User has been added!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to create user.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        $data = [
            'ID' => $user->id,
            'Parent ID' => $user->parent_id,
            'Root ID' => $user->root_id,
            'User Name' => $user->user_name,
            'Name' => $user->name,
            'Email' => $user->email,
            'Date Of Birth' => $user->date_of_birth,
            'Phone' => $user->phone,
            'Description' => $user->description,
            'Referral Code' => $user->referral_code,
        ];

        return $data;
    }

    /**
     * Display a user edit form.
     *
     * @param User $user
     *
     * @return Response
     */
    public function edit(User $user)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Edit User'));
        MetaTag::set('description', config('app.rankup.company_title').' Edit User Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        
        $userPlanStatus = UserPlanStatus::toSelectArray();
        
        return view('admin.users.create_update', compact('user', 'roles', 'userRole', 'userPlanStatus'));
    }

    /**
     * Update User
     *
     * @param User $user
     * @param UserRequest $request
     *
     * @return Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();

        if ($request->hasFile('video')) {
            if (isset($user->video)) {
                CommonUtil::removeFile($user->video);
            }
            $videoName = CommonUtil::uploadFileToFolder($request->file('video'), 'users/video');
            $data['video'] = $videoName;
        } else {
            $data['video'] = $user->video;
        }
        if ($request->hasFile('profile_image')) {
            if (isset($user->profile_image)) {
                CommonUtil::removeFile($user->profile_image);
            }
            $imageName = CommonUtil::uploadFileToFolder($request->file('profile_image'), 'users/image');
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('profile_image'), 'users/thumbnails');
            $data['profile_image'] = $imageName;
            $data['thumbnail_image'] = $thumbanilImage;
        } else {
            $data['profile_image'] = $user->profile_image;
            $data['thumbnail_image'] = $user->thumbnail_image;
        }
        if (!empty($data['parent_id'])) {
            $userDetail = User::where(['id' => $data['parent_id']])->first();
            if ($userDetail) {
                $emptyNodeId = User::findEmptyNode($userDetail->id);
                $data['parent_id'] = $emptyNodeId;
                $data['root_id'] = $userDetail->root_id ?? $userDetail->id;
            }
        }

        $userPlanUpdate = UserPlan::where('user_id', $user->id)->first();

        if(isset($userPlanUpdate) && !empty($userPlanUpdate)){
            if(!empty($data['expiration'])) {
                $expiration = isset($user->userPlan) && !empty($user->userPlan)? convertDateFormatWithTimezone(Carbon::parse($data['expiration'])->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM') : null;
                $status = $data['status'];
            } else {
                $expiration = $userPlanUpdate->expiration;
                $status =  $userPlanUpdate->status;
            }
        }
       
        if ($user->update($data)) {
            if(isset($userPlanUpdate) && !empty($userPlanUpdate)){
                $userPlanUpdate->expiration = $expiration;
                $userPlanUpdate->status = $status;
                $userPlanUpdate->save();
            }
            Session::flash('success', __('User has been updated!'));
            return redirect()->back();
        } else {
            Session::flash('success', __('Unable to update user.'));
        }
    }

    /**
     * Delete User
     *
     * @param User $user
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        if (isset($user->video)) {
            CommonUtil::removeFile($user->video);
        }
        if (isset($user->profile_image)) {
            CommonUtil::removeFile($user->profile_image);
        }
        if (!empty($user->userPlan)) {
            $user->userPlan()->delete();
        }
        $user->delete();
    }

    /**
     * unverified User View.
     */
    public function unverifiedUserView() 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Unverified Users'));
        MetaTag::set('description', config('app.rankup.company_title').' Unverified Users Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));

        return view('admin.users.unverified');
    }

    /**
     * Get all unVerifie User for listing
     */
    public function getunVerifiedUserData()
    {
        return Datatables::of(User::select('id', 'name', 'user_name', 'email')->whereNull('email_verified_at'))
            ->addColumn('action', function ($data) {
                return '<a href="javascript:;" data-url="' . route('users.verified', $data->id) . '" class="verified-user-btn btn verify-btn">'. __('verify') .'</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Set User Verified for listing
     */
    public function setverifiedUser($id)
    {
        $user = User::find($id);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return true;
    }

    /**
     * Import form
     *
     * @return Response
     */
    public function importForm()
    {
        return view('admin.users.import');
    }

    /**
     * User import
     *
     * @param ImportRequest $request
     *
     * @return Response
     */
    public function importUsers(ImportRequest $request)
    {
        $file = CommonUtil::uploadFileToFolder($request->file, 'import');
        $open = fopen(storage_path('app/public/' . $file), "r");
        $csvData = fgetcsv($open);

        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $batchSize = 2000;
        $usersToInsert = [];
        $i = 0;

        while(($csvData = fgetcsv($open, 1000, ",")) !== false) {
            $userFields = [
                'name' => !empty($csvData['4']) ? trim($csvData[4]) : null,
                'user_name' => !empty($csvData['5']) ? trim($csvData[5]) : trim($csvData['0']),
                'email' => !empty($csvData['6']) ? trim($csvData[6]) : null,
                'email_verified_at' => Carbon::now(),
                'phone' => (!empty($csvData['7']) && $csvData['7'] != '' && is_numeric($csvData['7'])) ? trim($csvData[7]) : null,
                'country' => !empty($csvData['8']) ? trim($csvData[8]) : null,
                'is_imported' => 1,
                'ambassador_id' => $csvData['0'],
                'sponsor_id' => $csvData['9'],
            ];

            // Check if a user with the same username and different email exists
            // $sameUsernameDiffEmail = User::where('user_name', $userFields['user_name'])
            //     ->where('email', '!=', $userFields['email'])
            //     ->exists();

            //if(!$sameUsernameDiffEmail) {
                if(config('app.rankup.main_seller_email') == 'seller@dev.com') {
                    $uplineUser = User::where(['email' => 'seller@dev.com'])->first();
                    if(!empty($uplineUser)) {
                        $emptyNodeId = User::findEmptyNode($uplineUser->id);
                        $userFields['parent_id'] = $emptyNodeId;
                        $userFields['root_id'] = $uplineUser->root_id ?? $uplineUser->id;
                    }
                }

                $usersToInsert[] = $userFields;

                $i++;

                if($i === $batchSize) {
                    User::upsert($usersToInsert, ['email'], [
                        'name', 'user_name', 'email', 'email_verified_at', 'phone', 'country', 'created_at', 'updated_at', 'is_imported'
                    ]);

                    // Reset the array and counter
                    $usersToInsert = [];
                    $i = 0;
                }
            //}
        }

        fclose($open);

        // Upsert any remaining records
        if(!empty($usersToInsert)) {
            User::upsert($usersToInsert, ['email'], [
                'name', 'user_name', 'email', 'email_verified_at', 'phone', 'country', 'created_at', 'updated_at', 'is_imported'
            ]);
        }

        // Flash a success message to the session
        Session::flash('success', __('Users imported successfully'));

        // Redirect back to the users index page
        return redirect()->route('users.index');
     }
}
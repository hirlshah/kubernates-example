<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Classes\Helper\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Classes\Helper\CommonUtil;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;
use App\Http\Requests\ApiRegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Hash;

class AuthController extends Controller
{
    /**
     * Register
     *
     * @param ApiRegisterRequest $request
     *
     * @return Response
     */
    public function register(ApiRegisterRequest $request) 
    {   
        $data = $request->all();
        if(!empty($data['user_name'])) {
            $sameUsernameDiffEmail = User::where('user_name', $data['user_name'])
            ->where('email', '!=', $data['email'])
            ->exists();

            if($sameUsernameDiffEmail) {
                return response()->json(['message' => __('User name already exist')], 404);
            }
        }
        
        $user = User::where('email', $data['email'])->whereNotNull('sso_token')->exists();
        if(!$user) {
            $data['sso_token'] = Str::random(20); 
        }

        $data['name'] = $data['first_name'];
        $data['email_verified_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $data['is_first_login'] = 1;
        $data['lang'] = 'en';
        
        $referralCode = new ReferralCode();
        $data['referral_code'] = $referralCode->createReferralCode();

        if($request->hasFile('profile_image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('profile_image'), 'users/image');
            $data['profile_image'] = $imageName;
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('profile_image'), 'users/thumbnails');
            $data['thumbnail_image'] = $thumbanilImage;
        } else if(!empty($data['profile_image'])) {
            $imageName = CommonUtil::uploadFileFromUrl($data['profile_image'], 'users/image');
            $data['profile_image'] = $imageName;

            $explodedImageName = explode('/', $data['profile_image']);
            $thumbanilImage = CommonUtil::uploadThumbnailFileToFolderScript($data['profile_image'], 'users/thumbnails', end($explodedImageName), 'jpg');
            $data['thumbnail_image'] = $thumbanilImage;
        }
        
        if(config('app.rankup.main_seller_email') == 'seller@dev.com') {
            $uplineUser = User::where(['email' => 'seller@dev.com'])->first();
            if(!empty($uplineUser)) {
                $emptyNodeId = User::findEmptyNode($uplineUser->id);
                $data['parent_id'] = $emptyNodeId;
                $data['root_id'] = $uplineUser->root_id ?? $uplineUser->id;
            }
        }
        
        $userDetail = User::where('email', $data['email'])->first();
        if(empty($userDetail) || !empty($userDetail) && $userDetail->is_imported == 1 && $userDetail->password == null) {
            $randomPassword = Str::random(8);
            $data['password'] = Hash::make($randomPassword);
        } else {
            $randomPassword = __('You already have a password!');
        }

        $data['statistic_flag'] = 1; // Set 1 so it won't show training videos

        $user = User::updateOrCreate(
            ['email' => $data['email']],
            $data
        );

        if($user) {
            $role = Role::where(['name' => 'Seller'])->first();
            $user->assignRole([$role->id]);
            $ssoToken = $user->sso_token;
            return response()->json(['sso_token' => $ssoToken, 'password' => $randomPassword, 'message' => __('Registration successfully done')], 200);
        } else {
            return response()->json(['message' => __('Something went wrong')], 404);
        }
    }
}
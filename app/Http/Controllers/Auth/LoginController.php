<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Classes\Helper\ReferralCode;
use http\Client\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use MetaTag;
use Socialite;
use Session;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'seller/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

	/**
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function login(Request $request)
	{
		$request->validate(
            [
                'email'        => ['required','email'],
                'password'     => ['required'],
            ],
            [
                'email.required'    => __('Email is required'),
                'password.required' => __('Password is required')
            ]
        );

		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			if(auth()->user()->hasRole('Admin')) {
				$url = url('admin/dashboard');
			} elseif(auth()->user()->hasRole('Seller')) {
				$url = url('seller/dashboard');
			} else{
				$url = url('/');
			}
            $user = Auth::user();
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();
            return redirect($url);
		}  else {
            return redirect('login')->withError( __('This account is not activated.'));
		}

		$this->incrementLoginAttempts($request);
		return $this->sendFailedLoginResponse($request);
	}

    /**
     * Display a login form.
     */
    public function showLoginForm() 
    {
        if(Session::get('is_registered')) {
            Session::forget('is_registered');
        }
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Login'));
        MetaTag::set('description', config('app.rankup.company_title').' Login Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('frontend.login');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $user = Auth::User();
        if (!empty($user) && $user->is_first_login == 1) {
            $user->is_first_login = 0;
            $user->save();
        }
        Auth::logout();
        return redirect('login');
    }

    /**
     * redirect from our site to the OAuth provider
     *
     * @param $provider
     *
     * @return Response
     */
    public function redirect($provider) 
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function Callback($provider)
    {
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $user       =   User::where(['email' => $userSocial->getEmail()])->first();
        if($user) {
            Auth::login($user);
            return redirect()->route('seller-dashboard');
        } else {
            $parentUser = User::where(['email' => config('app.rankup.main_seller_email')])->first();
            if (!empty($parentUser)) {
                $emptyNodeId = User::findEmptyNode($parentUser->id);
                $parentId = $emptyNodeId;
                $rootId = $parentUser->root_id ?? $parentUser->id;
            }
            $referralCode = new ReferralCode();
            $user = User::create([
                'parent_id' => isset($parentId) ? $parentId : NULL,
                'root_id' => isset($rootId) ? $rootId : NULL,
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'provider_id' => $userSocial->getId(),
                'provider' => $provider,
                'referral_code' =>  $referralCode->createReferralCode(),
                'email_verified_at' => Carbon::now(),
                'lang' => 'en'
            ]);
            return redirect()->route('seller-dashboard');
        }
    }

    /**
     * SSO login
     *
     * @param $ssoToken
     *
     * @return Response
     */
    public function ssoLogin($ssoToken) 
    {
        $user = User::where('sso_token', $ssoToken)->first();
        if(isset($user)) {
            Auth::login($user);
            if(Auth::check()) {
                $user->last_login = date('Y-m-d H:i:s');
                $user->save();
                return redirect()->route('seller-dashboard');
            }     
        }
        return response()->json(['message' => __('Unauthorized')], 401);
    }
}

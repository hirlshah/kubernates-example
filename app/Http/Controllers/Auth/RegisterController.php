<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Helper\CommonUtil;
use App\Classes\Helper\ReferralCode;
use App\Classes\Helper\StripeConnect;
use App\Http\Controllers\Controller;
use App\Models\StripeCard;
use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use MetaTag;
use Session;
use Spatie\Permission\Models\Role;
use Throwable;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['date_of_birth'] = convertDateFormatWithTimezone($request->date_of_birth, 'Y-m-d', 'Y-m-d', 'FRONT-TO-CRM');
        if ($request->hasFile('profile_image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('profile_image'), 'users/image');
            $data['profile_image'] = $imageName;
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('profile_image'), 'users/thumbnails');
            $data['thumbnail_image'] = $thumbanilImage;
        }
        $referralCode = new ReferralCode();
        $data['referral_code'] = $referralCode->createReferralCode();

        if(config('app.rankup.comapny_name') == "ibuumerang_rankup") {
            $data['statistic_flag'] = 1; // Set 1 so it won't show training videos
        }

        $response = $this->create($data);
        if ($response['success'] == true) {
            try {
                event(new Registered($response['user']));
            } catch (\Exception$e) {
                Log::error($e->getMessage());
            }
            Session::put('is_registered', true);
            return [
                'success' => true,
                'redirect_url' => route('register.success'),
                'redirect_thank_you' => route('register.thank-you'),
            ];
        } else {
            return [
                'success' => false,
                'message' => $response['message'],
            ];
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = array(
            'name.required' => __('First Name is required'),
            'last_name.required' => __('Last Name is required'),
            'user_name.required' => __('User Name is required'),
            'date_of_birth.required' => __('Date of birth is required'),
            'email.required' => __('Email is required'),
            'email.email' => __('Enter valid email'),
            'upline_email.email' => __('Enter valid email'),
            'password.required' => __('Password is required'),
            'confirm_password.required' => __('Confirm password is required'),
            'confirm_password.same' => __("The confirm password and password must match."),
            'user_name.regex' => __('Username only allows lowercase, numbers, ._and - without spaces'),
            'password.regex' => __('Password must have at least one lowercase, uppercase, number and special character'),
            'agree.required' => __('Please accept terms and conditions'),
            'city.required' => __('City is required'),
            'country.required' => __('Country is required'),
            'profile_image.required' => __('Please select your image'),
            'profile_image.mimes' => __('Image must be a file of type:jpg'),
            'profile_image.max' => __('File is too Big, please select a File less than ').env('IMAGE_FILE_SIZE').__(' MB'),
        );

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'regex:/^[a-z-0-9._-]*$/', 'unique:users,user_name'],
            'date_of_birth' => ['required'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'upline_email' => ['nullable', 'email'],
            'password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'],
            'confirm_password' => ['required', 'same:password'],
            'agree' => ['required'],
            'city' => ['required'],
            'country' => ['required'],
            'profile_image' => ['required', 'mimes:jpg,jpeg', 'max:'.env('IMAGE_UPLOAD_SIZE')],
        ], $message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function create(array $data)
    {
        DB::beginTransaction();

        try {
            $data['parent_id'] = null;
            $data['root_id'] = null;
            if (!empty(session('referral_code'))) {
                $user = User::where(['referral_code' => session('referral_code')])->first();
                if ($user) {
                    $emptyNodeId = User::findEmptyNode($user->id);
                    $data['parent_id'] = $emptyNodeId;
                    $data['root_id'] = $user->root_id ?? $user->id;
                }
            }
            
            if (empty($data['upline_email']) || isset($data['no_upline'])) {
                $user = User::where(['email' => config('app.rankup.main_seller_email')])->first();
                if (!empty($user)) {
                    $emptyNodeId = User::findEmptyNode($user->id);
                    $data['parent_id'] = $emptyNodeId;
                    $data['root_id'] = $user->root_id ?? $user->id;
                }
            }else if (!empty($data['upline_email']) && !isset($data['no_upline'])) {
                $user = User::where(['email' => $data['upline_email']])->first();
                if (!empty($user)) {
                    $emptyNodeId = User::findEmptyNode($user->id);
                    $data['parent_id'] = $emptyNodeId;
                    $data['root_id'] = $user->root_id ?? $user->id;
                } else {
                    $user = User::where(['email' => config('app.rankup.main_seller_email')])->first();
                    if (!empty($user)) {
                        $emptyNodeId = User::findEmptyNode($user->id);
                        $data['parent_id'] = $emptyNodeId;
                        $data['root_id'] = $user->root_id ?? $user->id;
                    }
                }
            }

            $referralCode = new ReferralCode();

            $user = User::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'user_name' => $data['user_name'],
                'date_of_birth' => $data['date_of_birth'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'profile_image' => $data['profile_image'],
                'thumbnail_image' => $data['thumbnail_image'],
                'parent_id' => $data['parent_id'],
                'root_id' => $data['root_id'],
                'city' => $data['city'],
                'country' => $data['country'],
                'referral_code' => $referralCode->createReferralCode(),
                'is_first_login' => 1,
                'lang' => 'en'
            ]);

            $role = Role::where(['name' => 'Seller'])->first();
            $user->assignRole([$role->id]);
            $this->assignDefaultTasks($user->id);

            DB::commit();
            return [
                'success' => true,
                'user' => $user,
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } catch (ValidationException $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        } catch (Throwable $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * validate register fields
     */
    public function validateRegisterFields(Request $request)
    {
        if ($request->type == 'upline') {
            $user = User::where(['email' => $request->email])->first();
	        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
		        return response()->json([
			        'status' => false,
			        'text' => __('Enter valid email'),
		        ]);
	        }elseif ($user) {
                return response()->json([
                    'status' => true,
                    'text' => __('You will be linked to your upline') . ' ' . $user->getFullName(),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'text' => __('No upline has been found with this email, so you will be in a new team'),
                ]);
            }
        }

        if ($request->type == 'email') {
            $user = User::where(['email' => $request->email])->first();
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
	            return response()->json([
		            'status' => false,
		            'text' => __('Enter valid email'),
	            ]);
            }elseif ($user) {
                return response()->json([
                    'status' => false,
                    'text' => __('Email has already been taken'),
                ]);
            } else {
	            return response()->json([
		            'status' => true,
	            ]);
            }
        }

        if ($request->type == 'user_name') {
        	$user = User::where(['user_name' => $request->user_name])->first();
            if(!preg_match('/^[a-z-0-9._-]*$/', $request->user_name)) {
	            return response()->json([
		            'status' => false,
		            'text' => __('Username only allows lowercase, numbers, ._and - without spaces'),
	            ]);
            }elseif ($user) {
                return response()->json([
                    'status' => false,
                    'text' => __('User name has already been taken'),
                ]);
            } else {
                return response()->json([
                    'status' => true,
                ]);
            }
        }
    }

    /**
     * Display a register form.
     */
    public function showRegistrationForm()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Register'));
        MetaTag::set('description', config('app.rankup.company_title').' Register Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $uplineEmail = $uplineName = '';
        if (!empty(session('referral_code'))) {
            $user = User::where(['referral_code' => session('referral_code')])->first();
            if ($user) {
                $uplineEmail = $user->email;
                $uplineName = $user->getFullName();
            }
        }
        return view('frontend.register', compact('uplineEmail', 'uplineName'));
    }

    /**
     * Display a register success form.
     */
    public function registerSuccess()
    {
        if(!Session::get('is_registered')) {
            return redirect()->route('register');
        }
        MetaTag::set('title', config('app.rankup.company_title').' - Success');
        MetaTag::set('description', config('app.rankup.company_title').' Success Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('frontend.success');
    }

     /**
     * View Register Thank you
     *
     * @return View
     */
    public function registerThankYou()
    {
        if(!Session::get('is_registered')) {
            return redirect()->route('register');
        }
        MetaTag::set('title', config('app.rankup.company_title').' - Thank You');
        MetaTag::set('description', config('app.rankup.company_title').' Thank You Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('frontend.register-thank-you');
    }

    /**
     * assign Defaut Tasks.
    */
    public function assignDefaultTasks($userId)
    {
        $tasks = Task::defaultTasks();
        if(!empty($tasks)){
            foreach($tasks as $defaultTask) {
                $task = new Task();
                $task->title = $defaultTask;
                $task->user_id = $userId;
                $task->repeat_days = "sun,mon,tue,wed,thu,fri,sat";
                $task->repeat_monday = 1;
                $task->repeat_tuesday = 1;
                $task->repeat_wednesday = 1;
                $task->repeat_thursday = 1;
                $task->repeat_friday = 1;
                $task->repeat_saturday = 1;
                $task->repeat_sunday = 1;
                $task->save();
            }
        }
    }
}
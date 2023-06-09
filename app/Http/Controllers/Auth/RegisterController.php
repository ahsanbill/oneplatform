<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\InternalSubscription;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\CommonMethods;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\PushNotificationController;
use App\Models\Address;
use App\Models\Profile;
use Carbon\Carbon;
use Mail;
use Illuminate\Http\Request;
use Session;
use Auth;
use PDF;
use App\Models\AgentContact;
use App\Models\UserChatGroup;

use App\Mail\User as MailUser;
use App\Mail\AgentContact as AgentContactMailer;

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
    protected $redirectTo = '/home';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function registerUser(Request $request){

        $user = User::where("email", $request->email)->first();
        if($user){
            Session::flash('error', "Email already exists");
            return redirect()->back();
        }else{

            if(!$request->has('verify_code') || (Session::has('verifyCode') && Session::get('verifyCode') == $request->verify_code)){

                if($request->has('user_id')){

                    $userId = $request->user_id;
                    $user = User::find($userId);
                    $address = $user->address;
                    $profile = $user->profile;
                }else{

                    $user = new User();
                    $address = new Address();
                    $profile = new Profile;
                }

                $user->name = isset($request->name) ? $request->name : trim($request->firstName.' '.$request->lastName);
                $user->first_name = $request->firstName;
                $user->surname = $request->lastName;
                $user->email = $request->email;
                $user->contact_number = isset($request->contact_number) ? $request->contact_number : NULL;
                $user->music_url = isset($request->music_url) ? $request->music_url : NULL;
                $user->password = bcrypt($request->password);
                $user->subscription_id = 0;
                $user->active = 1;
                $user->api_token = str_random(60);
                $user->manager_chat = isset($request->managerChat) && $request->managerChat == 1 ? 1 : NULL;
                $user->save();

                $address->alias = 'main address';
                $address->user_id = $user->id;
                $address->save();

                $profile->birth_date = Carbon::now();
                $profile->user_id = $user->id;
                $profile->save();

                $agentContact = AgentContact::where(['contact_id' => $user->id])->first();
                if($agentContact){

                    $agentContact->approved = 1;
                    $agentContact->save();

                    $user->active = 1;
                    $user->save();

                    if($agentContact->agreement_pdf && CommonMethods::fileExists(public_path('agent-agreements/').$agentContact->agreement_pdf)){
                        unlink(public_path('agent-agreements/').$agentContact->agreement_pdf);
                    }
                    $pdfName = strtoupper('aca_'.uniqid()).'.pdf';
                    $fileName = 'agent-agreements/'.$pdfName;
                    $terms = preg_replace('/\r|\n/', '</td></tr><tr><td>', $agentContact->terms);
                    $data = ['name' => $agentContact->name, 'email' => $agentContact->email, 'commission' => $agentContact->commission, 'terms' => $terms, 'agent' => $agentContact->agentUser, 'agreementSign' => $agentContact->agreement_sign];
                    PDF::loadView('pdf.agent-contact-agreement', $data)->setPaper('a4', 'portrait')->setWarnings(false)->save($fileName);
                    $agentContact->agreement_pdf = $pdfName;
                    $agentContact->save();

                    $userChatGroup = new UserChatGroup();
                    $userChatGroup->agent_id = $agentContact->agent_id;
                    $userChatGroup->contact_id = $agentContact->contact_id;
                    $userChatGroup->save();

                    $userChatGroup->mergePersonalChat();

                    $result = Mail::to($agentContact->agentUser->email)->bcc(Config('constants.bcc_email'))->send(new AgentContactMailer($agentContact->agentUser, $agentContact, [''], 'approved-for-agent'));
                    $userNotification = new UserNotificationController();
                    $request->request->add(['user' => $agentContact->agentUser->id, 'customer' => $agentContact->contactUser->id, 'type' => 'contact_approved_for_agent', 'source_id' => $agentContact->id]);
                    $response = json_decode($userNotification->create($request), true);

                    $result = Mail::to($agentContact->contactUser->email)->bcc(Config('constants.bcc_email'))->send(new AgentContactMailer($agentContact->agentUser, $agentContact, [''], 'approved-for-contact'));
                    $userNotification = new UserNotificationController();
                    $request->request->add(['customer' => $agentContact->agentUser->id, 'user' => $agentContact->contactUser->id, 'type' => 'contact_approved_for_contact', 'source_id' => $agentContact->id]);
                    $response = json_decode($userNotification->create($request), true);

                    Auth::login($user);
                    return redirect(route('user.startup.wizard'));
                }

                //$result = Mail::to($user->email)->send(new MailUser('emailVerification', $user));
                //$result = Mail::to($user->email)->send(new MailUser('initiateVetting', $user));
                //$result = Mail::to(Config('constants.admin_email'))->send(new MailUser('registrationRequest', $user));
                Session::forget('verifyCode');
                //Session::flash('activation_sent', '1');
                $result = Mail::to($user->email)->bcc('cotysostudios@gmail.com')->send(new MailUser('emailVerified', $user));
                $platformManager = User::find(config('constants.admins')['1platformagent']['user_id']);
                foreach ($platformManager->devices as $device) {
                                    
                    if(($device->platform == 'android' || $device->platform == 'ios') && $device->device_id != NULL){

                        $fcm = new PushNotificationController();
                        $return = $fcm->send($device->device_id, 'New user registration', 'Email: '.$user->email, $device->platform);
                    }
                }
                $userNotification = new UserNotificationController();
                $request->request->add(['user' => config('constants.admins')['1platformagent']['user_id'], 'customer' => $user->id, 'type' => 'new_user_to_platform_manager', 'source_id' => $user->id]);
                $response = json_decode($userNotification->create($request), true);
                Auth::login($user);
                $loginController = new LoginController();
                $url = $loginController->handleUserProAppRedirect($user, route('profile'));

                $userInternalSubscription = new InternalSubscription();
                $userInternalSubscription->user_id = $user->id;
                $userInternalSubscription->subscription_package = 'silver_0_0';
                $userInternalSubscription->subscription_status = 1;
                $userInternalSubscription->save();

                return redirect($url);
            }else{

                Session::flash('error', "Verification code is not correct");
                $data = [
                    'name'      => $request->name,
                    'firstName' => $request->firstName,
                    'lastName'  => $request->lastName,
                    'email'     => $request->email,
                    'contact'  => $request->contact_number,
                ];
                return redirect(route('register'))->with('register.data', $data);
            }
        }
    }

    public function showRegistrationForm(Request $request)
    {
        if(session::has('register.data')){

            $data = Session::get('register.data');
            Session::forget('register.data');
            $name = $data['name'];
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $email = $data['email'];
            $contact = $data['contact'];
        }
        $str_result = '0123456789ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $verifyCode = substr(str_shuffle($str_result), 0, 7);
        Session::put('verifyCode', $verifyCode);

        if(Session::has('register.with.user')){

            $userId = Session::get('register.with.user');
            Session::forget('register.with.user');
        }

        $data = [
            'verifyCode' => $verifyCode,
            'name'       => isset($name) ? $name : '',
            'lastName'   => isset($lastName) ? $lastName : '',
            'firstName'  => isset($firstName) ? $firstName : '',
            'email'      => isset($email) ? $email : '',
            'contact'    => isset($contact) ? $contact : '',
            'userId'     => isset($userId) ? $userId : null,
        ];
        return view( 'auth.register', $data );
    }

    public function getActivate($token){
        $user = User::where("api_token", $token)->first();
        if($user){
            $user->active = 1;
            $user->save();
            Session::flash("success", "Your account is successfully activated");

            $result = Mail::to($user->email)->send(new MailUser('emailVerified', $user));
            return redirect("login");
        } else {
            Session::flash("error", "Invalid activation token");
            return redirect("login");
        }
    }
}

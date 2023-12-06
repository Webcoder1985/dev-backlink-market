<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use GoogleReCaptchaV2;
use TimeHunter\LaravelGoogleReCaptchaV2\Validations\GoogleReCaptchaV2ValidationRule;

use Stevebauman\Location\Facades\Location;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showregistrationform()
    {
        $userIp = request()->ip();
        $locationData = Location::get($userIp);
        $country = true;
        if (empty($locationData)) {
            $country = false;
        }
        //$country=false;
        $countryLists = getCountry();
        return view('auth.register', [
            'country' => $country,
            'countryLists' => $countryLists
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {


        $userIp = request()->ip();
        $locationData = Location::get($userIp);
        $country = true;

        if ($data['action_register'] == "on") :
            return Validator::make($data, [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]);

        else :
            if (empty($locationData)) {
                $country = false;
            }



            if ($country == false) : // changed the code
                return Validator::make($data, [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8'],
                    'country' => ['required'],
                    'terms' => 'accepted',
                    //'g-recaptcha-response' => 'required|recaptcha',
                    'g-recaptcha-response' => [new GoogleReCaptchaV2ValidationRule()]
                ]);
            // validate recapctha here
            else :
                return Validator::make($data, [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8'],
                    'terms' => 'accepted',
                    //  'g-recaptcha-response' => 'required|recaptcha',
                    'g-recaptcha-response' => [new GoogleReCaptchaV2ValidationRule()]
                ]);
            endif;
        endif;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create($data)
    {


        //var_dump($data);
        $userIp = request()->ip();
        $locationData = Location::get($userIp);
        $dat = 'N/A';
        if ($data['action_register'] == "on") :
            if (!empty($locationData)) {
                $dat = $locationData->countryCode;
            }else{
                $dat="";
            }
            return User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'registration_ip' => $userIp,
                'last_login_ip' => $userIp,
            ]);
        else :
            if (!empty($locationData)) {
                $dat = $locationData->countryCode;
            } else {
                $dat = $data['country'];
            }
            //echo "TEST"; exit;
            return User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'country' => $dat,
                'registration_ip' => $userIp,
                'last_login_ip' => $userIp,
            ]);
        endif;
    }
}

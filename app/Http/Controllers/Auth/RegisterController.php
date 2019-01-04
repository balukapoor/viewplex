<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\EmailVerification;

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
    protected $redirectTo = '/tours';

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
            'phone' => 'nullable',
            'company' => 'required|string',
            'source' => 'required|string|max:255',
            'signed_agreement' => 'required',
            'country' => 'required'
            // 'dial_code' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create($data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            // 'phone' => $data['phone'],
            'company' => $data['company'],
            'user_source' => $data['source'],
            'signed_agreement' => 1,
            'public_domain' => $this->createDomainFromName($data['name']),
            // 'dial_code' => $data['dial_code'],
            'country_code' => $data['country']
        ]);
        
        $token = str_random(4);
        $user->token = $token;
        $user->save();
        \Mail::to($data['email'])->send(new EmailVerification($user));

        return $user;

    }


    /**
     * Generate unique domain id based off user name with id attached
     *
     * @param array $data
     * @return string $domain
     */

    private function createDomainFromName($name)
    {

        $name = str_replace(' ', '-', $name); // Replaces all spaces with hyphens.
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name); // Removes special chars.
        $name = preg_replace('/-+/', '-', $name); // Removes any double hythons.
        $randomNumber = rand(10000000, 99999990);
        return $name . $randomNumber;

    }


}

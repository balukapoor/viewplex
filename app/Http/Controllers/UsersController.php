<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tours as Tours;
use Auth;
use App\Plan as Plan;
use Carbon\Carbon;
use App\Http\Requests;
use Authy\AuthyApi as AuthyApi;
use Twilio\Rest\Client;


/**
 * Controller that handles all Methods related to the users
 * settings and funtionality. This includes Auth stuff and
 * doesn't include Subscription stuff.
 */
class UsersController extends Controller
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
     * Show the Settings Page.
     */
    public function settingsIndex()
    {
        return view('settings');
    }

    public function billingIndex() {
        return view('billing');        
    }

    public function billingChange() {
        return view('change-payment-method');        
    }


    /**
     * Method handled by post route that handles user settings updates.
     */
    public function settingsUpdate(Request $request, $setting)
    {

        // Accept only certain post params
        $acceptedParams = [ 'name', 'email', 'phone', 'company', 'profile-photo' ];

        $loopCount = 0;

        $paramsCount = count($acceptedParams);

        foreach ($acceptedParams as $param) {
            if ($setting != $param) {
                $loopCount++;
            }
        }

        if ($loopCount === $paramsCount) {
            return redirect('/user/settings');
        }

        // Name
        if ($setting == 'name') {
            $name = $request->input('name');
            $this->validate($request, [
                'name' => 'required|string|max:255'
            ]);
            Auth::user()->name = $name;
            Auth::user()->save();
            return redirect('/user/settings')->with('success', 'Name updated');
        }

        // Email
        if ($setting == 'email') {

            $email = $request->input('email');
            $confirmEmail = $request->input('email_confirmation');

            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users|confirmed'
            ]);

            Auth::user()->email = $email;
            Auth::user()->save();

            return redirect('/user/settings')->with('success', 'Email address updated');
        }

        // Phone
        if ($setting == 'phone') {

            $phone = $request->input('phone');
            $confirmPhone = $request->input('phone_confirmation');

            $this->validate($request, [
                'phone' => 'required|numeric|confirmed'
            ]);

            Auth::user()->phone = $phone;
            Auth::user()->verified = 1;
            Auth::user()->save();

            return redirect('/user/settings')->with('success', 'Phone number updated');
        }

        // Company
        if ($setting == 'company') {
            $company = $request->input('company');

            $this->validate($request, [
                'company' => 'string|max:255'
            ]);

            Auth::user()->company = $company;
            Auth::user()->save();

            return redirect('/user/settings')->with('success', 'Company updated');
        }

        // Profile photo
        if ($setting == 'profile-photo') {

            if ($request->hasFile('profile-photo')) {

                $photo = $request->file('profile-photo');

                $this->validate($request, [
                    'profile-photo' => 'max:2000|mimes:png,jpg,jpeg'
                ]);

                // Get uploaded Filename
                $filename = $photo->getClientOriginalName();
                // Strip out any spaces
                $filename = preg_replace('/\s+/', '', $filename);
                // append current timestamp
                $filename = time() . '-' . $filename;
                // Store
                $photo->storeAs('public/upload/' . Auth::user()->id . '/profile-photo', $filename);

                // Store DB
                Auth::user()->profile_picture = $filename;
                Auth::user()->save();

                return redirect('/user/settings')->with('success', 'Profile picture uploaded');
            } else {

                return redirect('/user/settings')->with('error', 'Something went wrong..');
            }
        }
    }


    /**
     * Method that deactivates all live tours that exceed the plan limit.
     */
    public function deactivateTours($plan) {

        $user = Auth::user();
        $liveTours = Tours::where('public', true)->where('user_id', $user->id)->get();
        $liveToursCount = $liveTours->count();
        $planMaxTours = Plan::where('subkey', $plan)->first()->max_tours;

        if ($liveToursCount > $planMaxTours) {
            $indexCount = 0;
            for ($i = $liveToursCount; $i > $planMaxTours; $i--) {
                $indexCount++;
                $liveTours[$indexCount]->public = 0;
                $liveTours[$indexCount]->save();
            }
        }

        return;
    }

    /**
     * Shows the account deactivation confirmation splash.
     */
    public function deactivateIndex()
    {
        return view('deactivate');
    }

    /**
     * Method that deactivates a users account.
     */
    public function deactivateAccount()
    {
        Auth::user()->account_deleted = 1;
        Auth::user()->deleted_at = Carbon::now();
        Auth::user()->save();
        Auth::logout();

        return redirect('deactivation-confirmation');
    }

    /**
     * Shows the account reactivation splash.
     */
    public function reactivateIndex()
    {
        if (Auth::user()->account_deleted) {
            return view('reactivate');
        } else {
            return redirect('tours');
        }
    }

    /**
     * Method that reactivates a users account.
     */
    public function reactivateAccount()
    {
        Auth::user()->account_deleted = 0;
        Auth::user()->deleted_at = null;
        Auth::user()->save();
        return redirect('tours')->with('success', 'Account Reactivated Sucessfully!');
    }


    /**
     * Shows the verify page only if user isnt already verified.
     */
    public function verifyIndex()
    {
        if (!Auth::user()->verified) {
            return view('verify');
        } else {
            return redirect('tours');
        }
    }

    /**
     * Method called by JS AJAX that calls on Authy api to
     * verify phone number and send code to it.
     */
    public function ajaxVerify(Request $request)
    {

        $data = $request->all();

        // if ($data['phone'] != $data['old-phone']) {
        //     $phone = $data['phone'];
        // } else {
        //     $phone = $data['old-phone'];
        // }
        // if ($data['dial-code'] != $data['old-dial-code']) {
        //     $dialCode = $data['dial-code'];
        // } else {
        //     $dialCode = $data['old-dial-code'];
        // }

        // $authyApi = new AuthyApi(getenv('AUTHY_API_KEY'));

        // $authyUser = $authyApi->registerUser(
        //     Auth::user()->email,
        //     $phone,
        //     $dialCode
        // );

        if ($data['token'] == Auth::user()->token) {

            Auth::user()->verified = 1;
            Auth::user()->save();

            // $sms = $authyApi->requestSms(Auth::user()->authy_id);

            // if ($sms->ok()) {
            //     echo 'message sent';
            //     return;
            // } else {
            //     dd($sms);
            // }
            return redirect('tours')->with('success', 'Thank you, your account is now verified.');

        } else {
            return redirect('user/verify-account')->with('error', 'Invalid verification code.');
        }

    }

    /**
     * Method called by JS AJAX that calls on Authy api to
     * verify sent verification code to Authy API servers.
     *
     * @return $status
     */
    public function ajaxVerifyCode(Request $request, AuthyApi $authyApi)
    {
        $token = $request->all()['authCode'];
        $verification = $authyApi->verifyToken(Auth::user()->authy_id, $token);

        if ($verification->ok()) {
            Auth::user()->verified = true;
            Auth::user()->save();
            echo 'success';
            return;
        } else {
            echo 'code invalid';
            return;
        }
    }

    /**
     * Method called after verification that redirects user to the
     * main application dashboard.
     */
    public function verifyRedirect()
    {
        return redirect('tours')->with('success', 'Thank you, your account is now verified.');
    }

     public function supportIndex()
    {
        return view('support');
    }


}
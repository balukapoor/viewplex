<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tours as Tours;
use Auth;
use App\Plan as Plan;
use App\Http\Requests;
use App\User;

/**
 * Controller that handles all Methods related to the users subscription
 * including Stripe functionality and other billing methods.
 */
class SubscriptionController extends Controller
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
     * Show the upgrade splash. This is the view displayed when the user
     * tries to make a tour live but has reached their subscription limit.
     */
    public function upgradeSplash()
    {
        return view('upgrade');
    }


    /**
     * Show the Billing page.
     */
    public function billingIndex()
    {

        if (!\App\Helpers\GlobalData::isFreeSub()) {
            return view('billing');
        } else {
            return redirect()->back();
        }

    }


    /**
     * Show the Subscription page.
     *
     * @return [object] [Current User Plan]
     */
    public function subscriptionIndex()
    {   
        $check = false;
        if (Auth::user()->subkey != 'free') {
            $check = true;
        }

        $key = Auth::user()->subkey;
    
        $plan = Plan::where('subkey', $key)->get();

        return view('subscription')->with([
            'plan' => $plan,
            'check' => $check
        ]);

    }

    /**
     * Show the subscription cancel confirmation page.
     */
    public function subscriptionCancelIndex()
    {

        return view('subscription-cancel');

    }

    /**
     * Post request to Cancel the subscription and
     * redirect to confirmation page.
     */
    public function subscriptionCancel()
    {

        $user = Auth::user();

        $user->subscription('viewplex-premium-' . $user->id)->cancel();

        $user->subkey = 'free';

        $user->save();

        $this->deactivateTours('free');

        return view('subscription-cancel-confirmation');

    }

    /**
     * Method to check subscription max tour limit and disable
     * any tours that excede that limit.
     */
    private function deactivateTours($plan) {

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
     * Shows the subscription upgrade page that displays all
     * the subsciptions that the user can be ugraded to.
     */
    public function subscriptionUpgradeIndex()
    {
        $plans = Plan::all();

        $plan = 'yearly';
        $upgrade = '';
        if(isset($_GET['plan'])) {
                $plan = $_GET['plan'];                
        }
        return view('subscription-upgrade')->with([
            'plans' => $plans,
            'subsc' => $plan
        ]);

    }


    /**
     * Shows the Order page where the user can either enter new card
     * details or use existing ones to start new subscription.
     */
    public function subscriptionOrderIndex()
    {

        if (isset($_GET['subscription'])) {

            $plans = Plan::all();
            $acceptedParams = [];
            $requestParam = $_GET['subscription'];
            $paramValid = false;

            foreach ($plans as $plan) {
                $acceptedParams[] = $plan->subkey;
            }

            foreach ($acceptedParams as $acceptedParam) {

                if ($acceptedParam == $requestParam) {
                    $paramValid = true;
                }

            }
            // $update_plan = Plan::find(2);        
            $plan = '';
            if(isset($_GET['plan'])) {
                $plan = $_GET['plan'];                 
            }

            // $update_plan->save();

            if ($paramValid) {

                $thisPlan = Plan::where('subkey', $requestParam)->get();

                return view('subscription-order')->with([
                    'plan' => $thisPlan[0],
                    'subsc' => $plan
                ]);

            } else {
                return redirect()->back();
            }


        } else {

            return redirect('user/subscription');

        }

    }

    /**
     * Method that processes an order. It determines if the user is a
     * new Stripe user and whether they're currently subscribed or not
     * then acts accordingly.
     */
    public function newSubscriptionRequest(Request $request)
    {

        $user = Auth::user();
        $inputs = $request->all();


        if($inputs['subsc'] == 'yearly') {
            $planKey = $inputs['plan'].'-yearly';
        } else {
            $planKey = $inputs['plan'];
        }

        if($inputs['subsc'] == 'yearly') {
            $plan = $inputs['plan'];
            $planMaxTours = Plan::where('subkey', $plan)->first()->max_tours;
            $user->max_tours =  $planMaxTours * 12;  
            $user->public_tours =  0;  
            $user->save();        
        } else {
            $plan = $inputs['plan'];
            $planMaxTours = Plan::where('subkey', $plan)->first()->max_tours;
            $user->max_tours =  $planMaxTours;  
            $user->public_tours =  0;          
            $user->save();
        }


        if (isset($inputs['stripeToken'])) {

            $paymentToken = $inputs['stripeToken'];

        } else {
            $paymentToken = null;
        }

        if (!$user->hasStripeId()) {

            if (!isset($inputs['stripeToken'])) {
                return redirect()->back()->with('error', 'There seems to be an issue. Please try again. IF the problem persists, please contact us and we will try to help.');
            }

            $user->createAsStripeCustomer($paymentToken);

        }

        if ($user->subkey == 'free') {

            $this->newSubscription($user, $planKey, $paymentToken);

            return redirect('order-confirmation');

        } else {
            $this->swapSubscription($user, $planKey);

            return redirect('order-confirmation');

        }

    }

    public function updateCardInfo(Request $request) {
            $user = Auth::user();
            $inputs = $request->all();
            // dd($inputs);
            $user->updateCard($inputs['stripeToken']);
            return redirect('update-confirm');            
    }

    /**
     * Method called by @newSubscriptionRequest method that calls stripe
     * and creates a new subscription for the user.
     */
    private function newSubscription($user, $planKey, $paymentToken)
    {

        try {
            $old_plan = 
            $user->newSubscription('viewplex-premium-'  . $user->id, $planKey)->create($paymentToken);
        }
        catch (\Stripe\Error\Card $error) {
            $error = $error->getJsonBody()['error'];
            return redirect()->back()->with('error', $error['message']);
        }

        $plan = explode('-', $planKey);

        $user->subkey = $plan[0];        
        $user->save();

    }

    /**
     * Method called by @newSubscriptionRequest method that calls stripe
     * and swaps an existing subscription to the newley selected one for the user.
     */
    private function swapSubscription($user, $planKey)
    {

        try {
            
            $user->subscription('viewplex-premium-' . $user->id)->swap($planKey);
        }
        catch (\Stripe\Error\Card $error) {
            $error = $error->getJsonBody()['error'];
            return redirect()->back()->with('error', $error['message']);
        }

        $plan = explode('-', $planKey);

        $user->subkey = $plan[0];
        $user->save();

    }

    /**
     * Shows the order confirmation splash.
     */
    public function orderConfirmation()
    {

        return view('subscription-order-confirmation');

    }

    public function upgradeCardIndex()
    {
        return view('change-payment-method');
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Illuminate\Contracts\Cookie\Factory;
use App\Tours as Tours;
use App\User as User;
use GuzzleHttp\Client;
use App\NoJsVisitor as NoJsVisitor;
use Browser;
use Auth;
use App\Plan as Plan;
use App\Mail\ContactMail;

/**
 * Controller that handles all Public Page Request that are not secured by any middleware.
 */
class PublicPageController extends Controller
{

    /**
     * Method to display public tour when visitor lands on route.
     *
     * @param [string] $domain [<description> The public domain parameter.]
     * @param [integer] $tourId [<description> The id of the tour wished to be viewed.]
     */

        /**
     * Method to display tour.
     */

    public function TourLikes(Request $request) {

        $inputs = $request->all();
        $tourId = $inputs['tour_id'];


        $tour = Tours::findOrFail($tourId);
        $tourkey = 'tourlikes_' . $tour->id;        

        if (!Session::has($tourkey)) {
            //setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            Session::put($tourkey, 1);
            $tour->likes += 1;
            $tour->save();  
        }
        return response()->json(array('likes'=> $tour->likes, 'tourkey' => $tourkey), 200);
    }

    public function viewPublicTour($userid, $tourId, Request $request) {

        $tour = Tours::findOrFail($tourId);//where('user_id', Auth::user()->id);
        // $tour_data = json_decode($tour->tour_data);
        $id = $tour->user_id;
        $user = User::findOrFail($id);
        // $cookie = \Cookie::make('name', 'balu', 120);
        $cookie_name = "visitor";
        $cookie_value = "visitor";
        // dd($cookie_name);

        // unset($_COOKIE[$cookie_name]);
        $tourkey = 'tour_' . $tour->id;
        if (!Session::has($tourkey)) {
            // setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            Session::put($tourkey, 1);
            $tour->views += 1;
            $tour->save();  
        }
 
        if($tour->archiveComplete)
            return redirect('tours')->with('error', 'Tour has been removed');

        // if($tour->archived)
        //     return redirect('tours')->with('error', 'Tour has been archived');
        

        if ($tour->exists() && $tour->public == 1) {

            // $tour = $tour->get();

            $rooms = json_decode($tour->tour_data);

            return view('360.360viewer')->with([
                'tour' => $tour,
                'rooms' => $rooms,
                'user' => $user
            ]);

        } elseif (Auth::check()) {
            $tour = Tours::where('id', $tourId)->first();
            
            if($tour->archiveComplete)
                return redirect('tours')->with('error', 'Tour has been removed');

            // if($tour->archived)
            //     return redirect('tours')->with('error', 'Tour has been archived');
        
            // $tour = $tour->get();
// dd($tour->tour_data);
            $rooms = json_decode($tour->tour_data);

            return view('360.360viewer')->with([
                'tour' => $tour,
                'rooms' => $rooms,
                'user' => $user
            ]);
        } else {

            return redirect('login')->with('error', 'Please login');            
        }
}

	public function viewTour($domain, $tourId) {
        // Get user by domain
        $user = User::where('public_domain', $domain)->first();
        // dd('test');
        if ($user) {

            $tour = Tours::where('id', $tourId)
                            ->where('user_id', $user->id)
                            ->where('public', 1);
                                    $date = $tour->updated_at;
        

            if ($tour->exists()) {

                $tour = $tour->get();

                $rooms = json_decode($tour[0]->tour_data);

                return view('360.360viewer')->with([
                    'tour' => $tour[0],
                    'rooms' => $rooms
                ]);

            } else {
                abort(404);
            }

        } else {
            abort(404);
        }
    } 



    /**
     * Shows the guide page. Uses the below guzzle method to grab data from
     * our WP REST API.
     */
    public function guideIndex()
    {

        // $result = $this->guzzle('guide');

        // $content = $result[0]->acf->items;

        return view('guide');
        // ->with([
        //     'guideData' => $content
        // ]);


    }


    /**
     * Shows the Terms & Conditions page. Uses the below guzzle
     * method to grab data from our WP REST API.
     */
    public function termsIndex() {

        // $result = $this->guzzle('terms-and-conditions');

        // $content = $result[0]->content->rendered;

        // $lastModified = $result[0]->modified;

        // $lastModified = strtotime($lastModified);

        // $lastModified = date('d/m/Y', $lastModified);

        return view('terms');
        // ->with([
        //     'content' => $content,
        //     'lastModified' => $lastModified
        // ]);

    }


    /**
     * Shows the Privacy Policy page. Uses the below guzzle
     * method to grab data from our WP REST API.
     */
    public function privacyIndex() {

        // $result = $this->guzzle('privacy-policy');

        // $content = $result[0]->content->rendered;

        // $lastModified = $result[0]->modified;

        // $lastModified = strtotime($lastModified);

        // $lastModified = date('d/m/Y', $lastModified);

        return view('privacy');
        // ->with([
        //     'content' => $content,
        //     'lastModified' => $lastModified
        // ]);

    }


    /**
     * Shows the Digital Milenium Copyright Act page.
     * Uses the below guzzle method to grab data from our WP REST API.
     */
    public function dmcaIndex() {

        // $result = $this->guzzle('dmca');

        // $content = $result[0]->content->rendered;

        // $lastModified = $result[0]->modified;

        // $lastModified = strtotime($lastModified);

        // $lastModified = date('d/m/Y', $lastModified);

        return view('dmca');
        // ->with([
        //     'content' => $content,
        //     'lastModified' => $lastModified
        // ]);

    }


    /**
     * Method that utilises the Guzzle HTTP library to retrieve
     * post data from our WP REST API.
     *
     * May need some work on error reporting in case our API site goes down.
     * We don't want users to see Fatal Laravel errors.
     *
     * @param [string] $[slug] [<description> The slug for the page we wish to query.]
     * @return [Array] [<description> Array of returned data.]
     */
    private function guzzle($slug)
    {


        $guzzle = new Client();


        $rest = $guzzle->get(getenv('WP_REST_API_ENDPOINT') . '/pages?slug=' . $slug);

        $result = $rest->getBody()->getContents();

        $result = json_decode($result);

        return $result;


    }

    /**
     * Shows the deactivation confirmation splash.
     */
    public function deactivationIndex()
    {
        return view('deactivation-confirmation');
    }

    /**
     * Shows the page when No Javascript is detected.
     * As a test, me a Joanna made an IP lookup that stores a visitors
     * ip and browser info if they ever landed on the no JS page.
     *
     * We were curious to actually how many visitors realistically won't
     * have Javascript turned on.
     */
    public function noJs()
    {

        $browser = new Browser();

        $ip = $_SERVER['REMOTE_ADDR'];

        // $ip = '72.229.28.185';
        // New york

        $location = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));

        if ($location['geoplugin_status'] == '200') {
            $city = $location['geoplugin_city'];
            $country = $location['geoplugin_countryName'];
            $lat = $location['geoplugin_latitude'];
            $lng = $location['geoplugin_longitude'];
            $browserName = $browser->getBrowser();
            $browserVersion = $browser->getVersion();
            $os = $browser->getPlatform();


            $vistor = new NoJsVisitor;

            $vistor->city = $city;
            $vistor->country = $country;
            $vistor->lat = $lat;
            $vistor->lng = $lng;
            $vistor->browser_name = $browserName;
            $vistor->browser_version = $browserVersion;
            $vistor->os = $os;

            if (!\Auth::guest()) {
                $user = \Auth::user()->id;
            } else {
                $user = 'null';
            }

            $vistor->viewplex_user = $user;

            $vistor->save();

        } else {
            $browserName = $browser->getBrowser();
            $browserVersion = $browser->getVersion();
            $os = $browser->getPlatform();

            $vistor = new NoJsVisitor;

            $vistor->city = 'private';
            $vistor->country = 'private';
            $vistor->lat = 'private';
            $vistor->lng = 'private';
            $vistor->browser_name = $browserName;
            $vistor->browser_version = $browserVersion;
            $vistor->os = $os;

            if (!\Auth::guest()) {
                $user = \Auth::user()->id;
            } else {
                $user = 'null';
            }

            $vistor->viewplex_user = $user;

            $vistor->save();
        }

        return view('errors.no-js');

    }

    public function PublicSubscription() {
         $plans = Plan::all();
         $plan = 'yearly';
            if(isset($_GET['plan'])) {
                    $plan = $_GET['plan'];                
            }
        return view('public-subscription')->with([
            'plans' => $plans,
            'subsc' => $plan            
        ]);
    }

    public function contact(Request $r) {
        $inputs = $r->all();
        \Mail::to('ghafoorsarmad@gmail.com')->send(new ContactMail($inputs));
        return redirect()->back()->with('success', 'Message has been sent!');        
    }

}


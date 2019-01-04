<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tours as Tours;
use App\Images as Image;
use App\User as User;
use Auth;
use Session;
use App\Plan as Plan;
use Storage;


/**
 * Controller that handles all Methods related to the Tours and the
 * functionality.
 */
class ToursController extends Controller
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
     * Show the Tours main browse interface.
     * This is the main application dashboard.
     */

    public function recurse_copy($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }

    public function index(Request $request)
    {
        $tours = Tours::all();

        $user = Auth::user();

        if ($user->first_login) {

            $user->first_login = false;
            $user->save();
            return redirect('/user/subscription/upgrade');

        }
        

        // Our filters
        $filters = [
            0 => [ 'name' => 'Newest First', 'param' => 'newest' ],
            1 => [ 'name' => 'Oldest First', 'param' => 'oldest' ],
            2 => [ 'name' => 'Name', 'param' => 'name' ],
            3 => [ 'name' => 'Only Public', 'param' => 'public' ]
        ];

        // Get request param
        $filter = $request->only('filter');

        if (isset($filter['filter'])) {
            $filter = $filter['filter'];
        }

        // Retrieve tours by filter
        if ($filter == $filters[0]['param']) {

            $tours = Tours::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(12);
            $activeFilter = $filters[0]['name'];

        } else if ($filter == $filters[1]['param']) {

            $tours = Tours::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->paginate(12);
            $activeFilter = $filters[1]['name'];

        } else if ($filter == $filters[2]['param']) {

            $tours = Tours::where('user_id', Auth::user()->id)->orderBy('name', 'desc')->paginate(12);
            $activeFilter = $filters[2]['name'];

        } else if ($filter == $filters[3]['param']) {

            $tours = Tours::where('user_id', Auth::user()->id)
                        ->where('public', 1)
                        ->paginate(12);
            $activeFilter = $filters[3]['name'];

        } else {

            $tours = Tours::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(12);
            $activeFilter = $filters[0]['name'];

        }

        
        $resultCount = $tours->total();
        
        $tourscollection = $tours->filter()->where('archiveComplete', 0);
        

        return view('tours')->with([
            'tours' => $tours,
            'tourscollection' => $tourscollection,
            'activeFilter' => $activeFilter,
            'filters' => $filters,
            'resultCount' => $resultCount
        ]);
    }


    /**
     * Method to make tour public. This is protected by middleware in the
     * routes file. If the users active tour count exceeds the subscription
     * limit, the middleware will redirect to an upgrade splash.
     */
    public function makeTourPublic($tourId)
    {

        $tour = Tours::find($tourId);

        $tour->public = 1;

        $user = Auth::user();

        $publicTours = $user->public_tours + 1;

        $user->public_tours = $publicTours;

        $user->save();
        $tour->save();

        return redirect()->back()->with('success', 'Your 360 Tour is now set to Public');

    }

    /**
     * Method to make a tour private.
     */
    public function makeTourPrivate($tourId)
    {

        $tour = Tours::find($tourId);

        $tour->public = 0;

        $user = Auth::user();

        $publicTours = $user->public_tours - 1;

        $user->public_tours = $publicTours;

        $user->save();
        $tour->save();

        return redirect()->back()->with('info', 'Your 360 Tour is now set to Private');

    }


    /**
     * Method that deletes a tour, removes all files from storage
     * and decrements users active_tours.
     */
    public function deleteTour($tourId)
    {

        $tour = Tours::find($tourId);
        $user = Auth::user();

        if ( $tour->public) {

            $publicTours = $user->public_tours - 1;
            $user->public_tours = $publicTours;

        }


        // Storage::deleteDirectory('public/upload/' . $user->id . '/tours/' . $tourId);

        $user->save();
        $tour->archived = 1;
        $tour->save();
        $public_path = public_path();
        if(Auth::user()->company) {
             $path = $public_path.'/upload/archive/'.preg_replace('/\s+/', '', Auth::user()->company).'/tours/'.$tourId;
         } else {
            $path = $public_path.'/upload/archive/'. preg_replace('/\s+/', '', Auth::user()->name).Auth::user()->id.'/tours/'.$tourId;
         }
       
        if (!file_exists($path)) {
        mkdir($path, 0777, true);
        }
        if(Auth::user()->company) {
        $this->recurse_copy($public_path.'/upload/'.preg_replace('/\s+/', '', Auth::user()->company).'/tours/'.$tourId,$public_path.'/upload/archive/'.preg_replace('/\s+/', '', Auth::user()->company).'/tours/'.$tourId);
    } else {

        $this->recurse_copy($public_path.'/upload/'. preg_replace('/\s+/', '', Auth::user()->name).Auth::user()->id.'/tours/'.$tourId,$public_path.'/upload/archive/'.  preg_replace('/\s+/', '', Auth::user()->name).Auth::user()->id.'/tours/'.$tourId);
    }

        return redirect()->back()->with('success', 'Your tour has been archived');

    }

    public function editTour($tourId) {
        $tour = Tours::find($tourId);
        if(!empty($_GET['remove_icon'])) {
            if($_GET['remove_icon'] == true) {
              $tour = Tours::find($tourId);
              $tour->icon = NULL;
              $tour->save();

            $company = preg_replace('/\s+/', '', Auth::user()->company);
            // $username = preg_replace('/\s+/', '', Auth::user()->name);
            $userid = Auth::user()->id;
            Storage::disk('uploads')->delete($company . '/tours/' . $tourId . '/thumbnail/icon.jpg');
            // dd($company . '/tours/' . $tourId . '/thumbnail/icon.jpg');
            return redirect()->back()->with('success', 'Your tour icon has been removed');              
        }

        }
        // dd($tour->name);
        return view('edit-tour')->with('tour', $tour);
    }

    public function updateTour($tourId, Request $r) {
        $tour = Tours::find($tourId);

        $input = request()->all();
        
       
        // $company = preg_replace('/\s+/', '', Auth::user()->company);
        // $username = preg_replace('/\s+/', '', Auth::user()->name);
        // $userid = Auth::user()->id;
        // if($company) {
        //     $r->icon->move(public_path('upload'.'/tours/' . $tourId . '/thumbnail/test.jpg'), $icon);

        //     // Storage::disk('uploads')->put($company . '/tours/' . $tourId . '/thumbnail/icon.jpg', $icon);
        // } else {
        //     // Storage::disk('uploads')->put($username . $userid . '/tours/' . $tourId . '/thumbnail/icon.jpg', $icon);
        // }

        $tour->name = $input['name'];
        $tour->address_postcode = $input['code'];
        $tour->bedrooms = $input['bedrooms'];
        $tour->bathrooms = $input['bathrooms'];
        $tour->kitchen = $input['kitchen'];
        $tour->save();

        if($r->hasFile('icon')) {
            $this->validate($r, [

            'icon' => 'required|image|mimes:jpeg,jpg',

            ]);
            $icon = 'icon.'.$r->icon->getClientOriginalExtension();

            $company = preg_replace('/\s+/', '', Auth::user()->company);
            
            $r->icon->move(public_path('upload/'.$company.'/tours/' . $tourId . '/thumbnail/'), $icon); 
            $tour->icon = 'icon';
            $tour->save();
        }

        return redirect()->back()->with('success', 'Your tour has been updated');
    }


    /** 
     * Method to handle the search results page.
     */
    public function searchResults(Request $request) {

        $query = $request->input('search');


        if ($query) {
            $tours = Tours::where('address_postcode', 'like', '%' . $query . '%')->paginate(12);

            $resultCount = $tours->total();

            return view('tours')->with([
                'tours' => $tours,
                'query' => $query,
                'resultCount' => $resultCount
            ]);
        } else {
            return redirect('tours');
        }

    }

}

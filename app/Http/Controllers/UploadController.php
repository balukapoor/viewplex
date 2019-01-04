<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Tours as Tour;
use App\User as User;
use Auth;
use Session;

/**
 * Controller that handles all Methods related to the Upload stage
 * of an individual tour.
 */
class UploadController extends Controller
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
     * Show the upload page.
     */
	public function uploadIndex()
	{	
		$remainingTourCount = \App\Helpers\GlobalData::getRemainingTourCount();
		// dd($remainingTourCount);

		if(Auth::user()->subkey=='free') {
			return redirect('user/subscription/upgrade')->with('error', 'Please choose a subscription plan to upload tours.');			
		}

		if ($remainingTourCount==='∞') {
			return view('360.360tool');
		}

		if($remainingTourCount===0) {
			return redirect('user/subscription/upgrade')->with('error', 'Please upgrade subscription to upload more tours.');			
		}
		
		return view('360.360tool');
	}


	/**
	 *
	 */
	public function ajaxMakeTour(Request $request)
	{

		$inputs = $request->all();

		$data = $inputs['data'];
		$userId = $inputs['userId'];
		$tourName = $inputs['tourName'];
		$tourPostcode = $inputs['tourPostcode'];
		$thumb = $inputs['thumb'];
		$images = $inputs['image']; //this

		$tour = new Tour();

		$tour->user_id = (int) $userId;
		$tour->public = 1;
		$tour->name = $tourName;
		$tour->address_postcode = $tourPostcode;
		$tour->tour_data = $data;
		$tour->archived = 0;
		$tour->archiveComplete = 0;
		$tour->connector = $data;
		// $tour->path_to_images = $image;
		$user = Auth::user();
		$user->public_tours +=1;
		$user->save();

		$tour->save();

		$tourId = $tour->id;
		$TourID = $tourId;

		$thumb = Image::make(file_get_contents($thumb))->encode('jpg');

		// $upload = Storage::put('public/upload/' . $userId . '/tours/' . $tourId . '/thumbnail/thumbnail.jpg', $thumb);
		$company = preg_replace('/\s+/', '', Auth::user()->company);
		$username = preg_replace('/\s+/', '', Auth::user()->name);
		$userid = Auth::user()->id;
		if($company) {
			Storage::disk('uploads')->put($company . '/tours/' . $tourId . '/thumbnail/thumbnail.jpg', $thumb);
		} else {
			Storage::disk('uploads')->put($username . $userid . '/tours/' . $tourId . '/thumbnail/thumbnail.jpg', $thumb);
		}
		
		$roomData = [];
		$j = 1;
		for ($i=0; $i<count($request->image); $i++)
        {
        
            $image = $inputs['image'][$i]; // store these names as that json here, as its recieving the val
//            dd($inputs);
//        $name = $inputs['tourName'];
            $name = time();
//        $name = $inputs['name'];
            $userId = $inputs['userId'];
//		$tourId = $inputs['tourId'];
            $tourId = $inputs['tourPostcode'];

            $image = Image::make(file_get_contents($image))->encode('jpg');

            // $upload = Storage::put('public/upload/' . $userId . '/tours/' . $tourId . '/rooms/Room '.$j.'.jpg', $image);

            // $upload1 = Storage::put('public/upload/' . $userId . '/tours/' . $tourId . '/rooms/thumbs/'.$name.'.jpg', $image);
         if($company) {
             Storage::disk('uploads')->put($company . '/tours/' . $TourID . '/rooms/thumbs/'.$name.'.jpg', $image);
             Storage::disk('uploads')->put($company . '/tours/' . $TourID . '/rooms/Room'.$j.'.jpg', $image);
         } else {
         	Storage::disk('uploads')->put($username . $userid . '/tours/' . $TourID . '/rooms/thumbs/'.$name.'.jpg', $image);
            Storage::disk('uploads')->put($username . $userid . '/tours/' . $TourID . '/rooms/Room'.$j.'.jpg', $image);
         }
            

	        $roomData[$i]['id'] = $i;
	        $roomData[$i]['links'] = $name;
	        $roomData[$i]['name'] = "Room".$j;

	        $base_url = url('/');
	    if($company) {
	    	$roomData[$i]['image'] = $base_url.'/upload/' . $company . '/tours/' . $TourID . '/rooms/' . $name . '.jpg';

	        $roomData[$i]['thumbnail'] = $base_url.'/upload/' . $company . '/tours/' . $TourID .'/rooms/Room'.$j.'.jpg'; 
	    } else {
	    	$roomData[$i]['image'] = $base_url.'/upload/' . $username . $userid . '/tours/' . $TourID . '/rooms/' . $name . '.jpg';

	        $roomData[$i]['thumbnail'] = $base_url.'/upload/' . $username . $userid . '/tours/' . $TourID . '/rooms/thumbs/' . $name . '.jpg'; 
	    }
	        
	        $j++;
            
        }

					
        $tour->tour_data = json_encode($roomData);
        $tour->save();

		$response = array(
            'status' => 'success',
            'tourId' => $tourId
        );

        return Response::json($response);

	}



	/**
	 *
	 */
	public function ajaxStoreImage(Request $request)
	{


		$response = array(
            'status' => 'success',
        );//yes I want to store like that

        return Response::json($response);

	}


	public function uploadSuccessRedirect(Request $request)
	{

		return redirect('tours')->with('success', 'Tour uploaded successfully!');

	}

}

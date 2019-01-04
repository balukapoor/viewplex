<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewerController extends Controller
{


	public function __construct()
	{
		$this->middleware('auth');
	}

	public function viewerIndex()
	{
		return view('360.360staticviewer');
	}

}

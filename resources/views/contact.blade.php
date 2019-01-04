@extends('layouts.main')
@section('page-name', 'contact')
@section('footer-assets')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style type="text/css">
	.contact input, .contact select {
	    width: 60% !important;
	}
</style>
@endsection

@section('content')
	<div class="container">
	  <h2>Contact us</h2>
	  <form class="contact" action="{{ url('contact') }}" method="POST">
	    {{ csrf_field() }}	  
	    <div class="form-group col-sm-8">
	      <label for="email">What industry do you work in?</label>
	      <input type="text" class="form-control" name="industry">
	    </div>
	    <div class="form-group col-sm-8">
	      <label for="pwd">Where did you hear about us?</label>
	      <input type="text" class="form-control" name="hear">
	    </div>	    
	    <div class="form-group col-sm-8">
	      <label for="pwd">What type of tours are you looking to provide?</label>
	      <select class="form-control" name="provide">
	      	<option>Houses</option>
	      	<option>Car</option>
	      	<option>Leisure Centre</option>
	      	<option>Restaurant</option>
	      </select>
	    </div>
	     <div class="form-group col-sm-8">
	      <label for="pwd">Roughly how many images inside each tour would you like?</label>
	      <input type="text" class="form-control" name="inside">
	    </div>
	    <div class="form-group col-sm-8">
	      <label for="pwd">How much money are you prepared to spend per tour?</label>
	      <input type="text" class="form-control"  name="spend">
	    </div>
	    <div class="form-group col-sm-8">
	      <label for="pwd">Mobile Number</label>
	      <input type="text" class="form-control" name="mobile">
	    </div>
	    <div class="form-group col-sm-8">
	      <label for="pwd">Email Address</label>
	      <input type="email" class="form-control" name="email">
	    </div>
	    <div class="form-group col-sm-8">
	    	<button type="submit" class="btn btn-default">Submit</button>	      
	    </div>
	  </form>
	</div>
@endsection
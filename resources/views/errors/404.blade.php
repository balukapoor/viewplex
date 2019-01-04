@extends('layouts.alert')

@section('page-name', '404')

@section('content')

<main class="app-main">

	<div class="app-main__overlay"></div>

	<section class="section-message">
		<div class="container">

			<h1 class="section-message__heading">
				Oops.. It looks like you've <br>
				hit a dead end..
			</h1>

			<p class="section-message__copy">
				Perhaps you typed in the page extension wrong or maybe you followed a dead link from another source? If you think this is an error on our part, please send a message via our <a href="{{ url('feedback') }}">feedback</a> page and we'll do our best to resolve the issue.
			</p>

			<a class="btn btn-primary green section-message__cta"
			   href="{{ url('/tours') }}"
			>
				Return Home
			</a>

		</div>
	</section>

</main>

@endsection

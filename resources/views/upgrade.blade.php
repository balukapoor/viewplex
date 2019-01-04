@extends('layouts.alert')

@section('page-name', 'upgrade')

@section('content')

<main class="app-main">

	<div class="app-main__bg-image"></div>

	<div class="app-main__overlay"></div>

	<section class="section-message">
		<div class="container">

			<h1 class="section-message__heading">
				Sorry, it looks like you've <br>
				reached your subscription limit..
			</h1>

			<a class="btn btn-primary green section-message__cta"
			   href="{{ url('/user/subscription') }}"
			>
				Upgrade Subscription
			</a>

		</div>
	</section>

</main>

@endsection

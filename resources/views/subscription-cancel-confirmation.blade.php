
@extends('layouts.alert')

@section('page-name', 'subscription-cancel-confirmation')

@section('content')

<main class="app-main">

	<div class="app-main__overlay"></div>

	<div class="app-main__bg-image"></div>

	<section class="section-message">
		<div class="container">

			<h1 class="section-message__heading">
				Your subscription has been cancelled
			</h1>

			<p class="section-message__copy">
				You can resubscribe at any time.
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


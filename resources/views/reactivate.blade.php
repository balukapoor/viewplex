@extends('layouts.alert')

@section('page-name', 'reactivate')

@section('content')

<main class="app-main">

	<div class="app-main__overlay"></div>

	<section class="section-message">
		<div class="container">

			<h1 class="section-message__heading">
				It's great to see you again!
			</h1>

			<p class="section-message__copy">
				You've returned with just enough time to reactivate your account. If you wish to continue using Viewplex, please click the reactivate button below.
			</p>

			<form method="POST" action="{{ url('/user/reactivation') }}">
				{{ csrf_field() }}
				<button class="btn btn-primary green section-message__cta" type="submit">
				Reactivate Account
				</button>
			</form>

		</div>
	</section>

</main>

@endsection

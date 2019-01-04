@extends('layouts.alert')

@section('page-name', 'js-message')

@section('content')

	<main class="app-main">

		<div class="app-main__bg-image"></div>

			<div class="app-main__overlay"></div>

			<section class="section-message">
				<div class="container">

					<h1 class="section-message__heading">
						Oops, it seems that JavaScript <br> 
						is blocked on this website.
					</h1>

					<p class="section-message__copy">
					  Please consider enabling the JavaScript to get the best expierence from Viewplex website.
					</p>
					<a href="http://www.enable-javascript.com/" target="_blank">
						<button class="btn btn-primary green section-message__cta" type="submit">
							How to enable JavaScript ?
						</button>
					</a>


				</div>
			</section>
	</main>

@endsection
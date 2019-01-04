@extends('layouts.alert')

@section('page-name', 'browser-compability')

@section('content')

	<main class="app-main">

		<div class="app-main__bg-image"></div>

			<div class="app-main__overlay"></div>

			<section class="section-message">
				<div class="container">

					<h1 class="section-message__heading">
						Oops, it seems that your browser is unsupported.<br>
					</h1>

					<p class="section-message__copy">
						Update or change your browser to view this website correctly.
					</p>

		            <a href="http://outdatedbrowser.com/en" target="_blank">
						<button class="btn btn-primary green section-message__cta" type="submit">
							Update my browser
						</button>
					</a>

				</div>
			</section>

	</main>

@endsection
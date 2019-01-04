@extends('layouts.alert')

@section('page-name', 'deactivate')

@section('content')

<main class="app-main">

	<div class="app-main__bg-image"></div>

	<div class="app-main__overlay"></div>

	<section class="section-message">
		<div class="container">

			<h1 class="section-message__heading">
				Sorry to see you go!
			</h1>

			<p class="section-message__copy">
				Your subscription, if any, has been cancelled and your live tours have been set to private.<br>
				Your virtual tours will be safe for 6 months and you can return at any time between now and then.
				<br><br>
				Thanks again,
				The Viewplex Team @ SourceCodeLabs
			</p>

		</div>
	</section>

</main>

@endsection

@extends('layouts.alert')

@section('page-name', 'deactivate')

@section('content')

<main class="app-main">

	<div class="app-main__bg-image"></div> 
	
        <div class="app-main__overlay"></div>

			<section class="section-message">
				<div class="container">

					<h1 class="section-message__heading">
						Are you sure you wish to <br>
						Deactivate your account?
					</h1>

					<p class="section-message__copy">
						You will lose your current subscription allowance and it will be sad to see you go..<br>
						Your virtual tours will be safe for 6 months and you can return at any time between now and then.
						Your current live tours will also become private.
					</p>

					<form method="POST" action="{{ url('/user/deactivate-account') }}">

						{{ csrf_field() }}

						<a class="btn btn-primary white outline section-message__cta"
						   href="{{ url('/user/settings') }}"
						>
							Cancel
						</a>

						<button class="btn btn-primary green section-message__cta" type="submit">
							Deactivate Account?
						</button>
					</form>

				</div>
			</section>

</main>

@endsection

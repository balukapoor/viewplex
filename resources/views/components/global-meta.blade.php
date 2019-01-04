<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1" />



@if (!Request::is('no-js'))
<noscript>
<meta http-equiv="refresh" content="0;URL='{{ url('no-js') }}'" />
</noscript>
@endif

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>
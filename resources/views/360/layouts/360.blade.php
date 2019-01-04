<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        @include('components/global-meta')
        @yield('meta')
        @include('components/global-head-assets')

        <link rel="stylesheet" type="text/css" href="{{ asset('css/360.css') }}">

        @yield('head-assets')
    </head>

    <body class="layout--360 page--@yield('page-name')">


        {{-- Main View --}}
        @yield('content')

        {{-- Scripts --}}

        @yield('footer-assets')

    </body>
</html>

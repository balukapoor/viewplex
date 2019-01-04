<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        @include('components/global-meta')
        @yield('meta')
        @include('components/global-head-assets')
        @yield('head-assets')

    </head>

    <body class="layout--general page--@yield('page-name')">

        <div class="app-main__bg-image"></div>
        <div class="app-main__overlay"></div>

        {{-- Flash Messages --}}
        @include('components/flash-messages')

        {{-- Header --}}
        @include('components/header')

        {{-- Main View --}}
        @yield('content')

        {{-- Footer --}}
        @include('components/footer')

        {{-- Scripts --}}
        @include('components/global-footer-assets')
        @yield('footer-assets')

    </body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        @include('components/global-meta')
        @yield('meta')
        @include('components/global-head-assets')
        @yield('head-assets')
    </head>

    <body class="layout--alert page--@yield('page-name')">

        {{-- Flash Messages --}}
        @include('components/flash-messages')

        {{-- Menu underlay --}}
        <div class="menu-underlay" data-close-menu></div>
        
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

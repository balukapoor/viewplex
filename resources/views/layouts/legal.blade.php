<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        @include('components/global-meta')
        @yield('meta')
        @include('components/global-head-assets')
        @yield('head-assets')

    </head>

    <body class="layout--legal layout--general page--@yield('page-name')">


        {{-- Flash Messages --}}
        @include('components/flash-messages')

        {{-- Header --}}
        @include('components/header')



        <main class="app-main">

            <div class="container">
                <div class="row">
                    <div class="col col-md-10 col-md-offset-1">
                        <section class="section-content">

                            <div class="section-content__container">

                                {{-- Main View --}}
                                @yield('content')

                            </div>

                        </section>
                    </div>
                </div>
            </div>

        </main>

        {{-- Footer --}}
        @include('components/footer')

        {{-- Scripts --}}
        @include('components/global-footer-assets')
        @yield('footer-assets')

    </body>
</html>

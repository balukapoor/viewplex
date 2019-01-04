@extends('360.layouts.360')
@section('page-name', '360Tool')

@section('head-assets')
<script src="{{ asset('js/360Tool.js') }}"></script>
@endsection

@section('content')

{{-- Flash Messages --}}
<div class="flash-message-box visible" data-flash-messages></div>

{{-- Header --}}
@include('components/header')

<script type="text/javascript">
    javascript:(function(){var script=document.createElement('script');script.onload=function(){var stats=new Stats();document.body.appendChild(stats.dom);requestAnimationFrame(function loop(){stats.update();requestAnimationFrame(loop)});};script.src='//rawgit.com/mrdoob/stats.js/master/build/stats.min.js';document.head.appendChild(script);})()

</script>
<main class="app-main">

    <section class="canvas-container">
        <div id="canvas-container"></div>
    </section>

    <div class="app-loader">
        <div class="app-loader__spinner">Loading...</div>
    </div>

    <aside class="uploads-sidebar">

        <div class="uploads-sidebar__gradient"></div>

        <div class="uploads-sidebar__overflow">
            <div class="uploads-sidebar__scroll">
                <div class="uploads-sidebar__inner">
                    <ul data-uploads-navigation>
                    {{-- Thumbs Appended here via JS::ViewerController.js --}}
                    </ul>
                </div>
            </div>
        </div>

    </aside>

    </footer>


</main>

{{-- Footer --}}
@include('components/footer')

@endsection

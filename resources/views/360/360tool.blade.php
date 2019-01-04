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

    <section class="canvas-container" data-360-space>
        @include('360.components.room-connector')
    </section>

    @include('360.components.renamer')
    @include('360.components.new-upload')

    <div class="app-loader">
        <div class="app-loader__spinner">Loading...</div>
    </div>

    <section class="upload-box">
        <div class="container">
            <div class="upload-box__flex">

                <div class="upload-box__inner">

                    <h1>New Tour</h1>
                    <p>Upload your 360 images and click begin</p>

                    {{-- Form Here --}}
                    <form class="upload-box__form main-form">

                        <div class="input-group">

                            <div class="input-wrap">
                                <i class="material-icons">backup</i>
                                <input type="file" name="images[]" multiple data-images-input>
                            </div>

                        </div>
                        <button class="btn btn-primary green" data-init-360>Begin</button>
                    </form>

                    <div class="upload-box__progress-bar" data-upload-progress-bar>
                        <div class="inner" data-upload-progress-bar-inner></div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <aside class="uploads-sidebar">

        <div class="uploads-sidebar__gradient"></div>

        <div class="uploads-sidebar__overflow">
            <div class="uploads-sidebar__scroll">
                <div class="uploads-sidebar__inner">
                    <ul data-uploads-navigation>
                    {{-- Thumbs Appended here via JS::UiController.js --}}
                    </ul>
                </div>
            </div>
        </div>

    </aside>

    <section id="textForConnector" class="text-holder">
        <ul class="textList" data-room-connector-text>
            {{-- Thumbs Appended here via JS::UiController.js --}}
        <!-- <a href="#" class="text-container" data-id="">
                <li class="textRoomConnector">Room 1</li>
            </a> -->
        </ul>
    </section>

    <footer class="uploads-toolbar">

        <div class="uploads-toolbar__controls">
            <ul>
                <li data-tool-toggle data-tool="newUpload" title="Add new photo">
                    <i class="material-icons">add_to_photos</i>
                </li>
                <li data-tool-toggle data-tool="roomConnector" title="Add room conector">
                    <i class="material-icons">adjust</i>
                </li>
                <li data-tool-toggle data-tool="removeRoomConnector" title="Delete room connector">
                    <i class="material-icons">cancel</i>
                </li>

            </ul>
        </div>

        <div class="uploads-toolbar__submit">
            <a href="{{ url('tours/new') }}" class="btn btn-primary cancel">Cancel</a>
            <button class="btn btn-primary outline white save" data-export-button>
                <span class="text">Save</span>
            </button>
        </div>

    </footer>

    <section id="contextTool" class="uploads-context-toolbar">

        <div class="uploads-context-toolbar__buttons">
            <ul>
                <li id="removeConnector" title="Remove this connector">
                    <i class="material-icons">cancel</i>
                </li>
                <!-- <li id="addTextConnector" title="Add additional text to this connector" class="">
                    <i class="material-icons">adjust</i>
                </li>
                <li id="moveConnector" title="Move this connector" class="">
                    <i class="material-icons">cancel</i>
                </li> -->

            </ul>
        </div>

    </section>


    <section class="export-window">

        <div class="export-window__inner">

            <div class="container">

                <div class="export-window__confirmation">
                    <h3>Are you ready to save your 360 Virtual Tour?</h3>
                    <div class="buttons">
                        <button class="btn btn-primary green outline" data-export-back>
                            <span class="text">Go back</span>
                        </button>
                        <button class="btn btn-primary green" data-export-confirm>Save</button>
                    </div>
                </div>

                <div class="export-window__progress">

                    <h3>Viewplex is now saving your tour.</h3>

                    <p>Depending on how many images you uploaded, this may take a while. Please be patient.</p>

                    <div class="export-window__progress-bar" data-upload-progress-bar>
                        <div class="inner" data-upload-progress-bar-inner></div>
                    </div>

                    <div class="hidden csrf-token" data-export-csrf="{{ csrf_token() }}"></div>
                    <div class="hidden user-id" data-user-id="{{ Auth::user()->id }}"></div>
                    <div class="hidden user-id" data-user-name="{{ Auth::user()->name }}"></div>

                </div>

            </div>

        </div>

    </section>


    <form class="hidden" method="POST" action="{{ url('tours/new/redirect') }}" data-export-finished-redirect>
        {{ csrf_field() }}
    </form>

</main>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js
"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
// $( "#dialog" ).hide();
    $('#add-icon').click(function(){
           $( function() {
            $( "#dialog" ).dialog();
          } );
    });
    $('#icon').click(function(){
        $( "#dialog" ).hide();
        var form = new FormData(); 
        form.append("icon-file", $("#fileInput")[0].files[0]);
    });
</script>
<style type="text/css">
    #dialog {
        display: none;
    }
</style>
{{-- Footer --}}
@include('components/footer')

@endsection

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        @include('components/global-meta')
        @yield('meta')
            <?php
                $scheme = App\Helpers\GlobalData::getColorScheme();
                if($scheme!='#B9B9B9') {
                    $color = $scheme;
                }
                else {
                    $color = '';                     
                }                  
            ?>
        @include('components/global-head-assets')
        @yield('head-assets')
        <style type="text/css">
            h1, h2, h3, h4, h6, p, a, span, select, label, input {
                color: <?php if($scheme!=FALSE) { ?>#4E8776 !important; <?php } ?>
            }
            .app-header, .app-footer {
                background: <?php echo $color; ?> !important;
            }
            .color_schemes {
                margin-top: 4px; 
                margin-bottom: 16px; 
                display: none;  
            }
        </style>
    </head>

    <body class="layout--main page--@yield('page-name')">

        {{-- Flash Messages --}}
        @include('components/flash-messages')

        {{-- Header --}}
        @include('components/header')

        {{-- Menu underlay --}}
        <div class="menu-underlay" data-close-menu></div>
        <!-- <div class="color_schemes">
            <a id="green" href="?color_scheme=48b9fe"></a>
            <a id="blue" href="?color_scheme=46e68a"></a>
            <a id="red" href="?color_scheme=4895e6"></a>
            <a id="default" href="?color_scheme=B9B9B9"></a>
        </div> -->
        <!-- <a href="" id="toggle" title="Change color scheme" class="arrow-down"></a> -->
        <style type="text/css">
            .arrow-down {
              width: 0; 
              height: 0; 
              border-left: 10px solid transparent;
              border-right: 10px solid transparent;
              border-top: 14px solid #7e7e22;
            }
            .arrow-up {
              width: 0; 
              height: 0; 
              border-left: 10px solid transparent;
              border-right: 10px solid transparent;
              border-bottom: 14px solid #7e7e22;
            }
            #green {
                background: #48b9fe; 
                padding: 2px 12px;
                margin-left: 1px;
            }
            #blue {
                background: #46e68a;  
                padding: 2px 12px;
                margin-left: 1px;
            }
            #red {
                background: #4895e6;  
                padding: 2px 12px;
                margin-left: 1px;
            }
            #default {
                background: #B9B9B9;  
                padding: 2px 12px;
                margin-left: 1px;
            }
        </style>
        {{-- Main View --}}
        @yield('content')

        {{-- Footer --}}
        @include('components/footer')

        {{-- Scripts --}}
        @include('components/global-footer-assets')
        @yield('footer-assets')

    </body>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
            $('#toggle').click(function() {
                $(".color_schemes").toggle(1000, function(){
                    // $("#toggle").toggleClass("arrow-up");            
                    // $("#toggle").removeClass("arrow-down");            
                });
                return false;
            }); 

   
    </script>
</html>

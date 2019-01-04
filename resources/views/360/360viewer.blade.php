@extends('360.layouts.360')
@section('page-name', '360Viewer')
<!-- Balu script -->
@section('head-assets')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js
"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/360Viewer.js') }}"></script>
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
<?php if($user->company) { ?> 
    <div class="hidden" data-image-base="{{ asset('upload/' . str_replace(' ','',$user->company) . '/tours/'. $tour->id .'/rooms') }}/"></div>
<?php } else { ?>
    <div class="hidden" data-image-base="{{ asset('upload/' . str_replace(' ','',$user->name) . $user->id . '/tours/'. $tour->id .'/rooms') }}/"></div>
<?php } ?>
    <div class="images hidden">
        @foreach ($rooms as $room)
            <?php if($user->company) { ?>       
            <div class="hidden"
                 data-image
                 data-image-src="{{ asset('upload/' . str_replace(' ','',$user->company) . '/tours/'. $tour->id .'/rooms/' . $room->name . '.jpg') }}"
            ></div>
            <?php } else { ?>
            <div class="hidden"
                 data-image
                 data-image-src="{{ asset('upload/' . str_replace(' ','',$user->name) . $user->id . '/tours/'. $tour->id .'/rooms/' . $room->name . '.jpg') }}"
            ></div>
            <?php } ?>
        @endforeach
    </div>

    <section class="canvas-container" data-360-space={{ $tour->connector }}></section>

    <div class="app-loader">
        <div class="app-loader__spinner">Loading...</div>
    </div>

    <aside class="uploads-sidebar">
        <div class="uploads-sidebar__gradient"></div>

        <div class="uploads-sidebar__overflow">
            <div class="uploads-sidebar__scroll">
                <div class="uploads-sidebar__inner">
                    <ul data-uploads-navigation>
                    <li class="uploads-sidebar__upload thumb-visible" id="shown" data-upload="" style="background-image: url(&quot;http://www.endlessicons.com/wp-content/uploads/2012/11/view-icon.png&quot;);"><span class="uploads-sidebar__upload-name" data-tool-toggle="" data-tool="renamer"></span></li>
                    {{-- Thumbs Appended here via JS::UiController.js --}}
                    </ul>
                </div>

            </div>            
        </div>
    </aside>
     <?php 

      $connect = json_decode($tour->connector, true);

      echo '<ul class="textList" data-room-connector-text>';
      $roomId = '';
      $connectedId = '';
      ?>

<section id="textForConnector" class="text-holder">
        <ul class="textList" data-room-connector-text>
            {{-- Thumbs Appended here via JS::UiController.js --}}
            <!-- <li id='0' class="textRoomConnector"><a href="#" class="text-container" data-id="" id="0"><i class="fa fa-chevron-circle-up" aria-hidden="true"></i>
            </a></li> -->
        </ul>
    </section>

    <footer class="uploads-toolbar">

        <div class="uploads-toolbar__controls">
            <ul>
               <!--  <li data-tool-toggle data-tool="newUpload">
                    <i class="material-icons">add_circle_outline</i>
                </li> -->
                   <li data-tool-toggle data-tool="newUpload" title="Add new photo">
                    <i class="material-icons">add_to_photos</i>
                </li>
                <li data-tool-toggle data-tool="roomConnector" title="Add room conector">
                    <i class="material-icons">adjust</i>
                </li>
                <li id="connect" data-tool-toggle data-tool="removeRoomConnector" title="Delete room connector">
                    <i class="material-icons">cancel</i>
                </li>

                <li class="loc" id="location" data-tool-toggle data-tool="roomConnector">
                    <i class="material-icons">room</i>
                </li>
                

            </ul>

        </div>
       <!-- I got these buttons from simplesharebuttons.com -->
<div id="share-buttons" title="Share">
 
    <!-- Facebook -->
    <a href="http://www.facebook.com/sharer.php?u={{ url()->current() }}" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/facebook.png" alt="Facebook" />
    </a>
    
    <!-- Google+ -->
    <a href="https://plus.google.com/share?url={{ url()->current() }}" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/google.png" alt="Google" />
    </a>
    
    <!-- LinkedIn -->
    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ url()->current() }}" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/linkedin.png" alt="LinkedIn" />
    </a>
    
    <!-- Pinterest -->
    <a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
        <img src="https://simplesharebuttons.com/images/somacro/pinterest.png" alt="Pinterest" />
    </a>

    <!-- Twitter -->
    <a href="https://twitter.com/share?url={{ url()->current() }}" target="_blank">
        <img src="https://simplesharebuttons.com/images/somacro/twitter.png" alt="Twitter" />
    </a>
    
        <!-- Email -->
    <a href="mailto:?Subject=Viewplex tours&amp;Body= https://simplesharebuttons.com">
        <img src="https://simplesharebuttons.com/images/somacro/email.png" alt="Email" />
    </a>

</div>
</div>
</div>  
<button class="btn btn-primary" id="share">SHARE</button>
        <?php if (empty(session('tourlikes_' . $tour->id))) { ?>
        <form method="POST" action="{{ url('/getmsg') }}">
         {{ csrf_field() }}
          <input type="hidden" name="tour_id" value="{{ $tour->id }}">
          <input type="submit" name="like" class="btn btn-primary cherry" value="Like">
        </form>
        <?php } else { ?>
        <form method="POST" action="{{ url('/getmsg') }}">
         {{ csrf_field() }}
          <input type="hidden" name="tour_id" value="{{ $tour->id }}">
          <input type="submit" name="like" class="btn btn-primary cherry" value="Liked" disabled="disabled">
        </form>
        <?php } ?>
        <span id='likes' style="color: white;margin-top: 67px;margin-left: -22px;">{{ $tour->likes }}</span>
                    <div class="icon"><img src="http://www.endlessicons.com/wp-content/uploads/2012/11/view-icon.png" height="50" width="50"><span style="color: white;margin-top: 67px;position: absolute;margin-left: -51px;">{{ $tour->views }}</span>
                   @if($tour->icon == 'icon') <img src="{{ asset('upload/' . str_replace(' ','',$user->company) .'/tours/'. $tour->id .'/thumbnail/icon.jpg') }}">
                   @endif
                    </div>

    </footer>

    <div class="hidden" data-tour-data='{{ $tour->tour_data }}' data-tour-connectors='{{ $tour->connector }}'></div>

    <div id="dialog" title="Tour Location">
      <p>{{ $tour->address_postcode }}</p>
    </div>
</main>


<script type="text/javascript">
 
$('.loc').click(function(){
  $( function() {
    $( "#dialog" ).dialog({
      show: {effect: 'fade', duration: 1000},
       hide: {effect: 'fade', duration: 1000}
    });
  } );
  return false
});

$(document).ready(function(){ 
          //Room connector starts
$('.text-container').click(function(){

    var id2 = $(this).attr('id');
    $(".uploads-sidebar li#"+id2+" .uploads-sidebar__upload-thumb").trigger("click");
    // $('#shown').trigger('click');
     

    var id1 = $(".uploads-sidebar__inner li.active").attr('id');
    $('.textList .textRoomConnector'+id).css('display', 'block');
    $('.textList li').css('display', 'none');
    $('li.textRoomConnector'+id1).css('display', 'block');

    return false;
});

$(".uploads-sidebar__inner li").click(function(){
    $('.textList li').css('display', 'none');


    var id = $(this).attr('id');;
    

    $('li.textRoomConnector'+id).css('display', 'block');
    // $('#shown').trigger('click');    

 });

  var id = $(".uploads-sidebar__inner li.active").attr('id');
  $('.textList .textRoomConnector'+id).css('display', 'block');


// room connector close
   
// alert('.textList li#'+id);
      $( "div canvas" ).first().remove();
      $('.uploads-sidebar__upload').removeClass('thumb-visible');
        $(document).delegate(".cherry","click",function(e){ 
            e.preventDefault();
            var idval = this.id;
            console.log(idval);
            var toud_id = "{{$tour->id}}";    
            $.ajax({
              url: '/likes',
              type: "post",
                    beforeSend: function (xhr) {
                    var token = $('input[name="_token"]').attr('value');

                    if (token) {
                          return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }                
                }, 
                data: {'id': idval, 'tour_id': toud_id},
                success:function(data){
                
                console.log(data.msg);
                $('input[type="submit"]').attr('disabled','disabled');
                $('input[type="submit"]').attr('value','Liked');
                $('#likes').html(data.likes);
                  // $( "."+idval ).load('../home  .'+idval);    
                  
                },error:function(){ 
                    console.log("error!!!!");
                } 
      });      
 });  

   $('.uploads-sidebar__inner').hover(       
     function () {
        $('#shown').addClass('thumb-visible');
        $('#shown').show('slow');
     }, function() {
        $('#shown').show('hide');

     });

    $('.uploads-sidebar__inner').hover(       
     function () {
        $('#shown').show('slow');      
    }, function(){
        $('.uploads-sidebar__upload').removeClass('thumb-visible'); 
        $('#shown').hide('slow');                       
    });


    $('#shown').click(function(){
      $('.uploads-sidebar__upload').toggleClass('thumb-visible');
      $('#shown').addClass('thumb-visible');  
      var id = $(".uploads-sidebar__inner li.active").attr('id');
      $('.textList .textRoomConnector'+id).css('display', 'block');          
        // $('#shown').hide('slow');
        // $('#eye').remove();
    });
});


$('#share').click(function(){
 $( function() {
    $( "#share-buttons" ).dialog({
       show: {effect: 'fade', duration: 1000},
       hide: {effect: 'fade', duration: 1000}
    });
  });
  return false
});

</script>
<?php
    $scheme = App\Helpers\GlobalData::getColorScheme();
    if($scheme!='#B9B9B9') {
        $color = $scheme;
    }
    else {
        $color = 'none';                     
    }                  
?>
<style type="text/css">
#textForConnector img {
    width: 60px;
}
#textForConnector {
    margin: 20% 28%;
    left: 77px;
}
.textRoomConnector {
    position: unset; !important;    
}
.textList li {
    display: none;
    font-size: 33px;
    /*color: white;*/
}
.textList li a{
  color: white;
}
.btn-primary {
  font-size: 12px !important;   
      padding: 8px !important;
    margin-left: 9px !important;                    
}
.ui-dialog.ui-corner-all.ui-widget.ui-widget-content.ui-front.ui-draggable.ui-resizable {
    background: transparent;
    padding: -2%;
}
  .uploads-sidebar__upload {
    background-size: 200px,200px;
    background-position: center;
    background-repeat: no-repeat;
   }
   .uploads-sidebar__upload {
      /*display: none;*/
   }
   .uploads-sidebar__upload {
      border: 5px solid <?php echo $color; ?> !important;
   }
   #eye {
     width: 5%;
    float: left;
    height: 8%;
  }
   form .cherry {
      padding: 8px;
  }
   #dialog, #share-buttons {
    display: none;  
   }
   #share-buttons {
    padding: 8px !important;
    margin-left: 9px !important;
   }
   .icon img{
    width: 100px;
    height: 100px;
   }
   #share-buttons img {
    width: 50px;
    height: 50px;
    float: left;
    margin-right: 8px;
  }
  #shown {
    display: none;
  }

</style>
{{-- Footer --}}
@include('components/footer')

@endsection

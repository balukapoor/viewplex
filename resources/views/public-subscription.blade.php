@extends('layouts.main')
@section('page-name', 'subscription-upgrade')

@section('content')

<main class="app-main">

    <section class="section-heading">
        <div class="container">

            <h1 class="section-heading__heading">
                Choose a plan
            </h1>
        </div>
    </section>

    <section class="section-plans">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-lg-offset-0 col-md-10 col-md-offset-1">
                    <div class="row">

                        @php
                        $bronzeFeatures = [
                            [ 'feature' => 'Access to our full feature Interface until subscription runs out', 'included' => true ],
                            [ 'feature' => '100 uploaded panoramas', 'included' => true ],
                            [ 'feature' => 'Free Support and future updates ', 'included' => true ],
                            [ 'feature' => 'Direct link of for your virtual tours to embed on Zoopla, Rightmove or your own website', 'included' => true ],
                            [ 'feature' => 'No Contract', 'included' => true ],
                            [ 'feature' => 'Full Statistics and Analytics of who visits your tours, how long they stay and where they usually click or tap', 'included' => false ],
                            [ 'feature' => 'Feature 5', 'included' => false ],
                        ];
                        $silverFeatures = [
                            [ 'feature' => 'Access to our full feature Interface until subscription runs out', 'included' => true ],
                            [ 'feature' => '500 uploaded panoramas ', 'included' => true ],
                            [ 'feature' => 'Free Support and future updates', 'included' => true ],
                            [ 'feature' => 'Direct link of for your virtual tours to embed on Zoopla, Rightmove or your own website', 'included' => true ],
                            [ 'feature' => 'No Contract', 'included' => true ],
                            [ 'feature' => 'Full Statistics and Analytics of who visits your tours, how long they stay and where they usually click or tap', 'included' => false ],
                            [ 'feature' => 'Feature 5', 'included' => false ],
                        ];
                        $goldFeatures = [
                            [ 'feature' => 'Access to our full feature Interface until subscription runs out', 'included' => true ],
                            [ 'feature' => '1000 uploaded panoramas', 'included' => true ],
                            [ 'feature' => 'Free Support and future updates', 'included' => true ],
                            [ 'feature' => 'Direct link of for your virtual tours to embed on Zoopla, Rightmove or your own website', 'included' => true ],
                            [ 'feature' => 'No Contract', 'included' => true ],
                            [ 'feature' => 'Full Statistics and Analytics of who visits your tours, how long they stay and where they usually click or tap', 'included' => false ],
                            [ 'feature' => 'Feature 5', 'included' => true ],
                        ];
                        $platinumFeatures = [
                            [ 'feature' => 'Access to our full feature Interface until subscription runs out', 'included' => true ],
                            [ 'feature' => 'Unlimited uploaded panoramas', 'included' => true ],
                            [ 'feature' => 'Free Support and future updates', 'included' => true ],
                            [ 'feature' => 'Direct link of for your virtual tours to embed on Zoopla, Rightmove or your own website', 'included' => true ],
                            [ 'feature' => 'No Contract', 'included' => true ],
                            [ 'feature' => 'Full Statistics and Analytics of who visits your tours, how long they stay and where they usually click or tap', 'included' => true ],
                            [ 'feature' => 'Feature 5', 'included' => true ],
                        ];
                        @endphp


                        @foreach($plans as $plan)
                            @if($plan->subkey != 'free')
                            <div class="section-plans__plan col-lg-3 col-md-6 {{ $plan->name }}">
                                <div class="section-plans__plan-inner">

                                    <div class="section-plans__plan-header">                            
                                        <h2 class="section-plans__plan-name">{{ $plan->name }}</h2>
                                 <?php  
                                $upgradePlan = '';
                                $url_plan = '';
                                if(isset($_GET['upgradePlan'])) {
                                    $upgradePlan = $_GET['upgradePlan'];
                                }
                                if(isset($_GET['plan'])) {
                                    $url_plan = $_GET['plan'];
                                }


                                ?>
                                <span class="section-plans__plan-price">
                                    @if($upgradePlan==$plan->subkey && $url_plan=='monthly')
                                             £{{ $plan->price }} /pm
                                        @else
                                             £{{ $plan->yearly }} /yearly
                                    @endif                                           
                                        </span>
                                        <span class="section-plans__plan-count">
                               @if ($plan->subkey == 'platinum')
                                            Unlimited                                  
                                    @elseif($upgradePlan==$plan->subkey && $url_plan=='monthly')
                                            {{ $plan->max_tours }} 
                                    @else 
                                             {{ $plan->max_tours * 12 }} 
                                @endif
                                    Tours
                                        </span>                        
                                    </div>
                                    
                                    <div class="section-plans__plan-body">

                                        <ul class="section-plans__plan-features">
                                            @php
                                            if ($plan->subkey == 'bronze') {
                                                $features = $bronzeFeatures;
                                            } elseif ($plan->subkey == 'silver') {
                                                $features = $silverFeatures;
                                            } elseif ($plan->subkey == 'gold') {
                                                $features = $goldFeatures;
                                            } elseif ($plan->subkey == 'platinum') {
                                                $features = $platinumFeatures;
                                            }
                                            @endphp

                                            @foreach ($features as $feature)

                                                @php
                                                    if ($feature['included']) {
                                                        $icon = 'check_circle';
                                                        $iconColor = 'green';
                                                    } else {
                                                        $icon = 'cancel';
                                                        $iconColor = 'red';
                                                    }
                                                @endphp

                                                <li>
                                                    <i class="material-icons {{ $iconColor }}">{{ $icon }}</i>
                                                    {{ $feature['feature'] }}
                                                </li>
                                            @endforeach

                                        </ul>

                                    </div>

                                @if ($plan->subkey == 'platinum')

                                <div class="section-plans__plan-footer">
                                     <a class="btn btn-primary green bronze"
                                           href="{{ url('/premium') }}"
                                        >
                                            Enquire Now
                                    </a>
                                       
                                </div>

                                @else
                                    <div class="section-plans__plan-footer">

                                    <select id="{{ $plan->subkey }}">
                                            <option disabled>Choose a plan</option>                                        
                                            <option selected <?php if($subsc=='yearly' && $upgradePlan==$plan->subkey) { ?> selected <?php } ?> value="yearly&upgradePlan={{ $plan->subkey }}">Yearly</option>                                        
                                            <option <?php if($subsc=='monthly' && $upgradePlan==$plan->subkey) { ?> selected <?php } ?> value="monthly&upgradePlan={{ $plan->subkey }}">Monthly</option>
                                    </select>
                                     <a class="btn btn-primary green bronze"
                                           href="{{ url('/user/subscription/order?subscription=' . $plan->subkey .'&plan='.$subsc) }}"
                                        >
                                            Subscribe
                                    </a>
                                       
                                    </div>
                            @endif

                                </div>
                            </div>
                            @endif

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
<style type="text/css">
    .btn.btn-primary.green.bronze {
        width: 215px;
        font-size: 16px;
        margin-top: 8px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.2.1.min.js
"></script>
<script type="text/javascript">
$(document).ready(function(){
         var pag = window.location.pathname;
    $("#bronze").change(function(){
        var val = $(this).val();
        // alert( pag );
        window.location.href = pag+'?plan='+val;
    });
    $("#silver").change(function(){
        var val = $(this).val();
        // alert( pag );
        window.location.href = pag+'?plan='+val;
    });
    $("#gold").change(function(){
        var val = $(this).val();
        // alert( pag );
        window.location.href = pag+'?plan='+val;
    });
    $("#platinum").change(function(){
        var val = $(this).val();
        // alert( pag );
        window.location.href = pag+'?plan='+val;
    });

});

</script>

@endsection

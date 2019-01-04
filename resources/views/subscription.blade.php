@extends('layouts.main')
@section('page-name', 'subscription')

@section('content')

<main class="app-main">

    <section class="section-nav">
        <div class="container">

            <ul>
                <li><a href="{{ url('/user/settings') }}">Settings</a></li>
                @if (!App\Helpers\GlobalData::isFreeSub())
                <li><a href="{{ url('/user/billing') }}">Billing</a></li>
                @endif
                <li class="active"><a href="{{ url('/user/subscription') }}">Subscription</a></li>
            </ul>

        </div>
    </section>

    <section class="section-heading {{ $plan[0]->name }}">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    <div class="section-heading__inner">

                        <div class="section-heading__text">
                            <div class="section-heading__plan-icon">
                                <i class="material-icons">subscriptions</i>
                            </div>

                            <h1 class="section-heading__plan-name">
                                You are currently subscribed to the {{ $plan[0]->name }} Package!
                            </h1>
                        </div>

                        <div class="section-heading__buttons">
                            @if (!App\Helpers\GlobalData::isFreeSub())
                            <a class="btn btn-primary outline green" href="{{ url('user/billing') }}">
                                <span class="text">View Billing</span>
                            </a>
                            @endif
                            @if ($plan[0]->name != 'Platinum')
                                @if ($check != true)
                                    <a class="btn btn-primary green" href="{{ url('user/subscription/upgrade') }}">Upgrade</a>
                                @endif
                            @endif

                            @if ($plan[0]->subkey != 'free')
                                @if($check==true)
                                <a class="cancel" href="{{ url('user/subscription/cancel') }}">Please Cancel your current Subscription to upgrade a new plan.</a>
                                @endif
                            @endif

                            @if ($plan[0]->subkey == 'platinum')
                            <a class="cancel" href="{{ url('user/subscription/upgrade') }}">Downgrade</a>
                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- <section class="section-plans">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">

                        @foreach ($plans as $plan)

                            <div class="col-md-4">

                                <div class="section-plans__plan">
                                    {{ $plan->name }}
                                </div>

                            </div>

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section> --}}

</main>

@endsection

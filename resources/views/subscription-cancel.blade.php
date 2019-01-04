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

    <section class="section-heading">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    <div class="section-heading__inner">

                        <div class="section-heading__text">

                            <h1 class="section-heading__plan-name">
                                Are you sure you wish to end your subscription?
                            </h1>

                        </div>

                        <p class="section-heading__copy">Your plan will be reverted to the Free Plan. Any subscriptions that are public will be set to private but you will still have access to your account, your tours and can re-subscribe at any time.
                        <br><br>
                        You will still have full access until the end of your billing cycle.
                        </p>

                        <div class="section-heading__buttons">

                            <form method="POST" action="{{ url('user/subscription/cancel') }}">

                                {{ csrf_field() }}

                                <a class="btn btn-primary outline green" href="{{ url('user/subscription') }}">
                                    <span class="text">Back</span>
                                </a>
                                <button class="btn btn-primary green" type="submit">Cancel Subscription</button>
                            </form>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

</main>

@endsection

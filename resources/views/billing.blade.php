@extends('layouts.main')
@section('page-name', 'billing')

@section('content')

    <main class="app-main">



            <div class="container">

                <section class="section-payment">

                    <div class="col-sm-12 col-md-offset-2 col-md-8">
                        <div class="row">

                        <h1 class="section-payment__heading">
                            Your registered card
                        </h1>
                    </div>

                             <div class="section-payment__existing-card">

                                    <img src="{{ asset('images/' . Auth::user()->card_brand . '.png') }}">

                                    <span>XXXX XXXX XXXX {{ Auth::user()->card_last_four }}</span>

                            </div>

                                <a class="btn btn-primary green section-message__cta"
                                   href="{{ url('/user/billing/change-payment-method') }}"
                                >
                                    Update payment method
                                </a>
                            </div>
                </section>
            </div>
        </main>

@endsection

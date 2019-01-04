@extends('layouts.main')
@section('page-name', 'verify')

@section('footer-assets')
<script src="{{ asset('js/components/country-select.js') }}"></script>
<!-- <script src="{{ asset('js/components/verify.js') }}"></script> -->
@endsection

@section('content')

<main class="app-main">

    {{-- <div class="dial-code-underlay"></div> --}}

    <section class="section-form">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <div class="section-form__inner">

                        <form class="section-form__phone-verify main-form" method="POST" action="verify-account">

                            <h1 class="section-form__heading">
                                We just need to verify you're<br>
                                not a robot!
                            </h1>

                            <div data-csrf-field>
                                {{ csrf_field() }}
                            </div>

                            {{-- Phone --}}
                            <div class="section-form__input-group">

                                <label>Email</label>

                                <div class="section-form__input-wrap input-wrap dial-code-dropdown"
                                     data-flag-src="{{ asset('images/flags') }}"
                                >

                                    @php
                                    $countries = App\Helpers\GlobalData::getCountries();
                                    @endphp

                                    <!-- <div class="dial-code-dropdown__button" data-open-dial-code-menu>

                                        <img src="" data-dial-code-flag>

                                        <span data-dial-code></span>

                                        <i class="material-icons">keyboard_arrow_down</i>
                                    </div> -->

                                   <!--  <div class="dial-code-dropdown__menu">
                                        <ul>

                                            @foreach ($countries as $country)
                                                @if (Auth::user()->dial_code == $country['dial_code'])

                                                    <li class="selected" data-country-code="{{ $country['country_code'] }}"
                                                        data-dial-value="{{ $country['dial_code'] }}">
                                                        <img src="{{ asset('images/flags/' . $country['country_code'] . '-32.png') }}">
                                                        <span class="country">{{ $country['name'] }} ({{ $country['dial_code'] }})</span>
                                                    </li>

                                                @else
                                                    <li data-country-code="{{ $country['country_code'] }}" data-dial-value="{{ $country['dial_code'] }}">
                                                        <img src="{{ asset('images/flags/' . $country['country_code'] . '-32.png') }}">
                                                        <span class="country">{{ $country['name'] }} ({{ $country['dial_code'] }})</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div> -->

                                    <!-- <input type="hidden" name="dial_code" data-dial-code-hidden-input>
 -->
                                   <!--  <input type="hidden" name="old_dial_code" value="{{ Auth::user()->dial_code }}">
                                    <input type="hidden" name="old_phone" value="{{ Auth::user()->phone }}"> -->

                                    <input class="section-form__user-input"
                                           type="text"
                                           name="token"
                                           placeholder="Please enter the verification code, sent to your email"
                                         required
                                    >
                                </div>

                            </div>

                            <button class="btn btn-primary green submit" data-ajax-verify>Email activation</button>

                        </form>

                        <div class="section-form__code main-form">

                            <h1 class="section-form__heading">
                            Enter the code sent to your number.
                            </h1>

                            <div class="input-wrap">
                                <input class="section-form__user-input"
                                       type="text"
                                       name="authy_code"
                                       placeholder="e.g. 762872"
                                       required
                                >
                            </div>

                            <button class="btn btn-primary green submit" data-ajax-verify-code>Verify</button>
                        </div>


                        <form method="POST" action="{{ url('/user/verify-account/redirect') }}" class="submit-form hidden">
                            {{ csrf_field() }}
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>

</main>

@endsection

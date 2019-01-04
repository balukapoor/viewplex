@extends('layouts.auth')

@section('page-name', 'register')

@section('footer-assets')
<script src="{{ asset('js/components/country-select.js') }}"></script>
@endsection

@section('content')

<main class="app-main">

    <div class="dial-code-underlay" data-close-dial-code-menu></div>

    <section class="section-form">


        <div class="section-form__overlay"></div>

        <div class="section-form__inner">

            <h1 class="section-form__title">Register</h1>

            <!-- Form -->
            <form class="section-form__form main-form" method="POST" action="{{ route('register') }}">

                <h2>Your Details</h2>

                {{ csrf_field() }}

                <div class="section-form__inputs">


                    {{-- Name --}}
                    <div class="section-form__input-group">

                        <label>Name</label>

                        <div class="section-form__input-wrap input-wrap with-icon">
                            <i class="material-icons custom-icons">person</i>
                            <input class="section-form__user-input"
                                   type="text"
                                   name="name"
                                   placeholder="John Smith"
                                   value="{{ old('name') }}" required autofocus
                            >
                        </div>

                    </div>

                    {{-- Email --}}
                    <div class="section-form__input-group">

                        <label>Email</label>

                        <div class="section-form__input-wrap input-wrap with-icon">
                            <i class="material-icons custom-icons">email</i>
                            <input class="section-form__user-input"
                                   type="email"
                                   name="email"
                                   placeholder="example@company.com"
                                   value="{{ old('email') }}" required autofocus
                            >
                        </div>

                    </div>

                    {{-- Phone --}}
                    <!-- <div class="section-form__input-group">

                        <label>Mobile Number (for verification)</label>

                        <div class="section-form__input-wrap input-wrap dial-code-dropdown"
                             data-flag-src="{{ asset('images/flags') }}"
                        >

                            @php
                            $countries = App\Helpers\GlobalData::getCountries();
                            @endphp

                            <div class="dial-code-dropdown__button" data-open-dial-code-menu>

                                <img src="" data-dial-code-flag>

                                <span data-dial-code></span>

                                <i class="material-icons">keyboard_arrow_down</i>
                            </div>

                            <div class="dial-code-dropdown__menu">
                                <ul>

                                    @php($selected = false)

                                    @foreach ($countries as $country)
                                        @if (old('dial_code') == $country['dial_code'] && !$selected)

                                            @php($selected = true)
                                            <li class="selected" data-country-code="{{ $country['country_code'] }}"
                                                data-dial-value="{{ $country['dial_code'] }}">
                                                <img src="{{ asset('/images/flags/' . $country['country_code'] . '-32.png') }}">
                                                <span class="country">{{ $country['name'] }} ({{ $country['dial_code'] }})</span>
                                            </li>

                                        @elseif($country['country_code'] == 'GB' && !old('dial_code'))
                                            @php($selected = true)
                                            <li class="selected" data-country-code="{{ $country['country_code'] }}" data-dial-value="{{ $country['dial_code'] }}">
                                                <img src="{{ asset('/images/flags/' . $country['country_code'] . '-32.png') }}">
                                                <span class="country">{{ $country['name'] }} ({{ $country['dial_code'] }})</span>
                                            </li>
                                        @else
                                            <li data-country-code="{{ $country['country_code'] }}" data-dial-value="{{ $country['dial_code'] }}">
                                                <img src="{{ asset('/images/flags/' . $country['country_code'] . '-32.png') }}">
                                                <span class="country">{{ $country['name'] }} ({{ $country['dial_code'] }})</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>

                            <input type="hidden" name="dial_code" data-dial-code-hidden-input>

                            <input class="section-form__user-input"
                                   type="tel"
                                   name="phone"
                                   placeholder="07456 789 901"
                                   value="{{ old('phone') }}" required autofocus
                            >
                        </div>

                    </div> -->

                    {{-- Company --}}
                    <div class="section-form__input-group">

                        <label>Company</label>

                        <div class="section-form__input-wrap input-wrap with-icon">
                            <i class="material-icons custom-icons">business</i>
                            <input class="section-form__user-input"
                                   type="text"
                                   name="company"
                                   placeholder="Your company name"
                                   value="{{ old('company') }}" required autofocus
                            >
                        </div>

                    </div>

                    {{-- Password --}}
                    <div class="section-form__input-group">

                        <label>Password</label>

                        <div class="section-form__input-wrap input-wrap with-icon">
                            <i class="material-icons custom-icons">lock_outline</i>
                            <input class="section-form__user-input"
                                   type="password"
                                   name="password"
                                   placeholder="password"
                                   id="password"
                                   required
                            >
                        </div>

                    </div>

                    {{-- Confirm Password --}}
                    <div class="section-form__input-group">

                        <label>Confirm Password</label>

                        <div class="section-form__input-wrap input-wrap with-icon">
                            <i class="material-icons custom-icons">lock_outline</i>
                            <input class="section-form__user-input"
                                   type="password"
                                   name="password_confirmation"
                                   placeholder="password"
                                   id="password"
                                   required
                            >
                        </div>

                    </div>

                    {{-- Country --}}
                    <div class="section-form__input-group">

                        <label>Country</label>

                        <div class="section-form__input-wrap input-wrap with-icon select">

                            <i class="material-icons custom-icons">forum</i>
                            <i class="material-icons">keyboard_arrow_down</i>

                            <select class="section-form__user-input" name="country" required>

                                <option value="null">Please select..</option>

                                @foreach ($countries as $country)
                                    @if (old('country') != $country['country_code'])
                                    <option value="{{ $country['country_code'] }}">{{ $country['name'] }}</option>
                                    @else
                                    <option selected value="{{ $country['country_code'] }}">{{ $country['name'] }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>

                    </div>

                    {{-- Survey --}}
                    <div class="section-form__input-group select">

                        <label>Where did you first hear about Viewplex?</label>

                        <div class="section-form__input-wrap input-wrap with-icon select">

                            @php
                            $options = [
                                'Search Engine',
                                'Google Ad',
                                'A friend or coleage recommended us',
                                'other'
                            ];
                            @endphp

                            <i class="material-icons custom-icons">forum</i>
                            <i class="material-icons">keyboard_arrow_down</i>

                            <select class="section-form__user-input" name="source" required>

                                <option value="null">Please select..</option>

                                @foreach ($options as $option)
                                    @if (old('source') != $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                    @else
                                    <option selected value="{{ $option }}">{{ $option }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="section-form__input-group button">
                        <button type="submit"
                                class="auth-header__user-button submit"
                        >Register</button>
                        <label class="terms">
                            <input type="checkbox" name="signed_agreement" value="1" required> By registering for Viewplex, I agree to the <a href="{{ url('legal/terms-and-conditions') }}">Terms &amp; Conditions</a>
                        </label>
                    </div>

                </div>
            </form>

        </div>
    </section>


</main>

@endsection

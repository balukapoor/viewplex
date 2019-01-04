@extends('layouts.auth')

@section('page-name', 'login')

@section('content')

<main class="app-main">

    <section class="section-form">


        <div class="section-form__overlay"></div>

        <div class="section-form__inner">

            <h1 class="section-form__title">Login To Viewplex</h1>

            <!-- Form -->
            <form class="section-form__form main-form" method="POST" action="{{ route('login') }}">

                {{ csrf_field() }}

                <div class="section-form__inputs">
                    <div class="section-form__input-group">

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
                    <div class="section-form__input-group">

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

                    <div class="section-form__input-group button">
                        <button type="submit"
                                class="auth-header__user-button submit"
                        >Login</button>
                    </div>

                    <div class="section-form__input-group remember hidden">

                        <div class="section-form__input-wrap input-wrap">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" checked> Remember Me
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

            <div class="section-form__links">
                <h6>
                Forgot your <a href="{{ route('password.request') }}">password</a>?
                </h6>
            </div>
        </div>
    </section>


</main>

@endsection

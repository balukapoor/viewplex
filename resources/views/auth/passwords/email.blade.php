@extends('layouts.auth')

@section('page-name', 'reset')

@section('content')

<main class="app-main">

    <section class="section-form">


        <div class="section-form__overlay"></div>

        <div class="section-form__inner">

            <h1 class="section-form__title">Reset password</h1>

            <!-- Form -->
            <form class="section-form__form main-form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <div class="section-form__inputs">
                            <div class="section-form__input-group">
                                <div class="section-form__input-wrap input-wrap with-icon">
                                    <i class="material-icons custom-icons">email</i>
                                    <input class="section-form__user-input" 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    placeholder="example@company.com" 
                                    value="{{ old('email') }}" required
                                    >
                                </div>
                            </div>
                        </div>
                </div>

                        <div class="section-form__input-group button">
                            <button type="submit" class="auth-header__user-button submit">
                                Send Password Reset Link
                            </button>
                        </div>
                </div>
            </form>

        </div>
    </section>
@endsection

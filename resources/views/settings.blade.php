@extends('layouts.main')
@section('page-name', 'settings')

@section('footer-assets')
<script src="{{ asset('js/components/settings-modal.js') }}"></script>
@endsection

@section('content')

<main class="app-main">

    <section class="section-nav">
        <div class="container">

            <ul>
                <li class="active"><a href="{{ url('/user/settings') }}">Settings</a></li>

                @if (!App\Helpers\GlobalData::isFreeSub())
                <li><a href="{{ url('/user/billing') }}">Billing</a></li>
                @endif

                <li><a href="{{ url('/user/subscription') }}">Subscription</a></li>
            </ul>

        </div>
    </section>

    <div class="container">
        <section class="section-settings">

            <div class="section-settings__inner">

                @if (Auth::user()->profile_picture)
                    @php
                    $picture = Storage::url('public/upload/' . Auth::user()->id . '/profile-photo/' . Auth::user()->profile_picture);
                    $picture = 'style="background-image: url(' . $picture . ');"';
                    @endphp
                @else
                    @php($picture = '')
                @endif

                <div class="section-settings__photo" {!! $picture !!}>

                    <div class="upload-button"
                         data-open-form-modal
                         data-action="{{ url('/user/settings/profile-photo') }}"
                         data-names='["profile-photo"]'
                         data-nice-names='["Upload profile photo"]'
                         data-input-type="file"
                    >
                        <i class="material-icons">cloud_upload</i>
                    </div>
                </div>

                <div class="section-settings__options">
                    <ul>
                        <li>
                            <i class="material-icons">person</i>
                            <span>{{ \Auth::user()->name }}</span>
                            <a data-open-form-modal
                               data-action="{{ url('/user/settings/name') }}"
                               data-names='["name"]'
                               data-nice-names='["Name"]'
                               data-input-type="text"
                            >
                               (change)
                            </a>
                        </li>
                        <li>
                            <i class="material-icons">email</i>
                            <span>{{ \Auth::user()->email }}</span>
                            <a data-open-form-modal
                               data-action="{{ url('/user/settings/email') }}"
                               data-names='["email", "email_confirmation"]'
                               data-nice-names='["Email", "Confirm email"]'
                               data-input-type="email"
                            >(change)</a>
                        </li>
                        <li>
                            <i class="material-icons">phone</i>
                            <span>{{ \Auth::user()->phone }}</span>
                            <a data-open-form-modal
                               data-action="{{ url('/user/settings/phone') }}"
                               data-names='["phone", "phone_confirmation"]'
                               data-nice-names='["Phone Number", "Confirm phone"]'
                               data-input-type="tel"
                            >(change)</a>
                        </li>
                        <li>
                            <i class="material-icons">business</i>
                            <span>{{ \Auth::user()->company }}</span>
                            <a data-open-form-modal
                               data-action="{{ url('/user/settings/company') }}"
                               data-names='["company"]'
                               data-nice-names='["Company"]'
                               data-input-type="text"
                            >(change)</a>
                        </li>
                        <li>
                            <i class="material-icons">lock_outline</i>
                            <span>**********</span>
                            <a href="{{ url('password/reset') }}">(change)</a>
                        </li>
                        <li>
                            <a href="{{ url('/user/deactivate-account') }}">Deactivate Account?</a>
                        </li>
                    </ul>

                </div>

            </div>

        </section>
    </div>

    <div class="form-modal">

        <div class="form-modal__underlay" data-close-form-modal></div>

        <form class="form-modal__inner" method="POST" action="" enctype="multipart/form-data">

            {{ csrf_field() }}

            <div class="form-modal__input-area"></div>

        </form>

    </div>



</main>

@endsection

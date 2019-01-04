<div class="flash-message-box" data-flash-messages>

    @if (Session::has('error'))
        <div class="flash-message error">
            <i class="material-icons">error_outline</i>
            <span>{{ Session::get('error') }}</span>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="flash-message success">
            <i class="material-icons">check_circle</i>
            <span>{{ Session::get('success') }}</span>
        </div>
    @endif

    @if (Session::has('info'))
        <div class="flash-message info">
            <i class="material-icons">info</i>
            <span>{{ Session::get('info') }}</span>
        </div>
    @endif


    @if (isset($errors))
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="flash-message error">
                    <i class="material-icons">error_outline</i>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        @endif
    @endif

</div>
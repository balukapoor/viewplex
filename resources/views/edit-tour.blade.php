@extends('layouts.main')
@section('page-name', 'settings')

@section('footer-assets')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="{{ asset('js/components/settings-modal.js') }}"></script>
@endsection

@section('content')

<main class="app-main">

    <div class="container">

        <section class="section-settings">
              <h3>Edit tour</h3>

            <div class="form-group">

                <form class="form-modal__inner" method="POST" action="{{ url('tours/'.  $tour->id.'/update') }}" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <label for="Tour name">Tour name:</label><input class="form-control" type="text" name="name" value="{{ $tour->name }}"><br>
                    <label>Post-code:</label><input class="form-control" type="text" name="code" value="{{ $tour->address_postcode }}"><br>
                       <label>Bedrooms:</label><input class="form-control" type="text" name="bedrooms" value="{{ $tour->bedrooms }}"><br>
                          <label>Bathrooms:</label><input class="form-control" type="text" name="bathrooms" value="{{ $tour->bathrooms }}"><br>
                           <label>Kitchen:</label><input class="form-control" type="text" name="kitchen" value="{{ $tour->kitchen }}"><br>
                          
                           <label>Tour Icon:</label><input type="file" name="icon"><br>
                           @if($tour->icon=='icon')<img class="icon" src="{{ asset('upload/' . str_replace(' ','',Auth::user()->company) .'/tours/'. $tour->id .'/thumbnail/icon.jpg') }}">&nbsp;<span><a href="{{ url('tours') }}/{{ $tour->id }}/edit/?remove_icon=true">Remove</a></span>
                           @endif<br><br>
                          <button type="submit" name="update" class="btn btn-default">Update</button>                     
                </form>

            </div>

        </section>
    </div>
</main>
<style type="text/css">
  .icon{
    width: 100px !important;
    height: 100px !important;
   }
</style>
@endsection

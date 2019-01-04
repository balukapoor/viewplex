@extends('layouts.main')

@section('page-name', 'support')

@section('content')

<main class="app-main">
    
    <div class="app-main__bg-image"></div>

    <div class="app-main__overlay"></div>

    <section class="section-form">

        <div class="container">
            
            <div class="section-support__container">
                <div class="section-support__icon">
                    <i class="material-icons">help_outline</i>
                </div>
                <div class="section-support__description">
                    <h1>Support</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam mollis elementum congue. Praesent imperdiet diam tempor, elementum nisi non, congue nibh. Duis lorem lectus, suscipit ut eleifend gravida, facilisis a dolor. In tincidunt dolor at purus hendrerit, sed ultrices velit volutpat. Praesent ac purus at metus fringilla tincidunt. Praesent tellus elit, ultricies vel enim ac, congue pretium libero. Etiam vitae sollicitudin leo.</p>
                </div>
            </div>
  

            <div class="row">
                <div class="col-sm-4">
                    <a href="{{ url('/guide') }}">
                        <div class="section-support__element">
                            <h2>Guide</h2>
                            <i class="material-icons">local_library</i>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ url('/faqs') }}">
                        <div class="section-support__element">
                            <h2>FAQ's</h2>
                            <i class="material-icons">insert_comment</i>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ url('/concatus') }}">
                        <div class="section-support__element">
                            <h2>Contact us</h2>
                            <i class="material-icons">mail</i>
                        </div>
                    </a>
                </div>
            </div>
            



        </div>
        
    </section>


</main>

    
    
@endsection


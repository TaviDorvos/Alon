<div id="layout-animation">
    @extends('layouts.master')
</div>

@section('content')

@if( auth()-> check() )
<div id="animation-home" class="container">
    <!-- Centered logo -->
    <div class="logo-div text-center text-md-start">
        <img id="logo" src="{{ URL::asset('images/logo_white.png') }}">
    </div>

    <!-- Search bar -->
    <div class="search text-center text-md-start">
        <form method="GET" action="search-results" role="search">
            {{ csrf_field() }}
            <div class="search-input d-flex justify-content-center align-items-center">
                <!-- Search icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16 " style="color: #80868b;">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
                <!-- Search input -->
                <input class="form-control" type="search" id="search-bar" name="search-bar" placeholder="Everything you need is here..." required>
            </div>
            <div class="search-buttons mt-5">
                <!-- Submit button for the search -->
                <button type="submit" id="search-button">Search</button>
            </div>
        </form>
    </div>
</div>
@else

<div class="container">
    @include('partials.mini-logo')
    <div class="logo-div text-center">
        <h1 style="font-size: 24px;"><b>Please login or register before using Alon.</b></h1>
    </div>
</div>
@endif
@endsection
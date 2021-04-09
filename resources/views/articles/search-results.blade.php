@extends('layouts.master')

@section('content')

@include('partials.mini-logo')
@if( auth()-> check() )
<div class="container">
    @if($articles->isNotEmpty())
    <!-- Search bar -->
    <div class="search text-center">
        <form method="GET" action="search-results" role="search">
            {{ csrf_field() }}
            <div class="mx-auto search-input d-flex align-items-center">
                <!-- Search icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16 " style="color: #80868b;">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
                <!-- Search input -->
                <input class="form-control" type="search" id="search-bar" name="search-bar" placeholder="Everything you need is here..." required>
            </div>
            <div class="search-buttons mt-3 mb-3">
                <!-- Submit button for the search -->
                <button type="submit" id="search-button">Search</button>
            </div>
        </form>
    </div>
    <div class="search-results">
        @foreach($articles as $article)
        <div class="result">
            <a href="{{ $article->url }}">
                <h3>{{ $article->title }}</h3>
                {{ $article->url }}
            </a>
            <p class="time-created text-end">Created at: {{ $article->created_at }}</p>
        </div>
        @endforeach
    </div>
    @else
    <div>
        <h2 class="mb-5">No articles found.</h2>
        <!-- Search bar -->
        <div class="search">
            <form method="GET" action="search-results" role="search">
                {{ csrf_field() }}
                <div class="search-input d-flex align-items-center">
                    <!-- Search icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16 " style="color: #80868b;">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                    <!-- Search input -->
                    <input class="form-control" type="search" id="search-bar" name="search-bar" placeholder="Everything you need is here..." required>
                </div>
                <div class="search-buttons mt-3">
                    <!-- Submit button for the search -->
                    <button type="submit" id="search-button">Search</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@else
<div class="logo-div text-center">
    <h1 style="font-size: 16px;"><b>Please login or register before using my Google Clone</b></h1>
</div>
@endif

@endsection
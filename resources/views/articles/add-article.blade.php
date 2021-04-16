@extends('layouts.master')

@section('content')

@include('partials.mini-logo')
<div class="container text-center text-md-start">
    @if( auth()-> check() )
    @if ( auth()->user()->role == 'admin' )
    <div id="add-article-container" class="mx-auto row justify-content-center">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form id="add-article" method="post" action="/add-article">
                {{csrf_field()}}
                <h1>Add new article:</h1>
                <h2 style="font-size: 14px;">Please add an URL to create a new article:</h2>
                <!-- <div class="form-group mb-3 mt-3">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div> -->
                <div class="form-group mb-3 mt-3">
                    <label for="url">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" required>
                </div>
                <!-- <div class="form-group mb-3 mt-3">
                    <label for="article-text">Article text:</label>
                    <textarea class="form-control" id="article-text" name="article-text" rows="10" required></textarea>
                </div> -->
                @if(count($errors))
                <div class="form-group">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <button type="submit" class="btn-custom">Add</button>
            </form>
        </div>
    </div>
    @else
    <script>
        window.location.href = "{{ url('/') }}";
    </script>
    @endif
    @else
    <div class="logo-div text-center">
        <h1 style="font-size: 16px;"><b>Please login or register before using my Google Clone</b></h1>
    </div>
    @endif
</div>
@endsection
@extends('layouts.master')

@section('content')

@include('partials.mini-logo')
<div class="container text-center text-md-start">
    <div id="login-container" class="mx-auto row justify-content-center align-self-center">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form id="login" method="post" action="/login">
                {{csrf_field()}}
                <h1>Login</h1>
                <h2 style="font-size: 14px;">Please fill in your credentials to login.</h2>
                <div class="form-group mb-3 mt-3">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group mb-3 mt-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
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
                @if (\Session::has('message'))
                <div class="alert alert-success">
                    <ul>
                        <li>{{ \Session::get('message') }}</li>
                    </ul>
                </div>
                @endif
                <div class="d-flex justify-content-center justify-content-md-end  form-group">
                    <label style="font-size: 14px;" class="mb-3">
                        <a href="{{ url('/forgot_password') }}">Forgot password?</a>
                    </label>
                </div>
                <button type="submit" class="btn-custom">Login</button>
            </form>
            <h2 class="mt-4" style="font-size: 14px;">Don't have an account? <a href="/register">Register here</a></h2>
        </div>
    </div>
</div>
@endsection
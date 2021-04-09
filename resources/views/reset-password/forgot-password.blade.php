@extends('layouts.master')

@section('content')
@include('partials.mini-logo')

<div class="container text-center text-md-start">
    <div id="forgot-password-container" class="mx-auto row justify-content-center">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form id="forgot_password" method="post" action="{{ url('/send_email') }}">
                {{csrf_field()}}
                <h1>Forgot Password</h1>
                <h2 style="font-size: 14px;">Please insert your email in order to reset your password:</h2>
                <div class="form-group mb-3 mt-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                @if(session('error'))
                <div class="form-group">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
                @endif
                @if((session('success')))
                <div class="form-group">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
                @endif
                <button type="submit" class="btn-custom">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection
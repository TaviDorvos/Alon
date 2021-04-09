@extends('layouts.master')

@section('content')

@include('partials.mini-logo')
<div class="container text-center text-md-start">
    <div id="reset-password-container" class="mx-auto row justify-content-center">
        <div class="col-sm-10 col-md-8 col-lg-6">
            <form id="reset-password" method="post" action="/reset-password">
                {{csrf_field()}}
                <input type="hidden" name="token" value ="{{ $token }}">
                <h1 class="mb-3">Change your password</h1>
                <h2 style="font-size: 14px;">Dont't worry, things like that are happening very often. <br>Just insert your new password:</h2>
                <div class="form-group mb-3 mt-3">
                    <label for="email">E-mail:</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $email }}"readonly>
                </div>
                <div class="form-group mb-3 mt-3">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group mb-3 mt-3">
                    <label for="password_confirmation">Password Confirmation:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
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
                <button type="submit" class="btn-custom">Reset</button>
            </form>
        </div>
    </div>
</div>
@endsection
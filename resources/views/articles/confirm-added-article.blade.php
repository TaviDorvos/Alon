@if( auth()-> check() )
@extends('layouts.master')

@section('content')
<div class="container">
    @include('partials.mini-logo')
    <div class="logo-div text-center">
        <h1 style="font-size: 16px;"><b>Thank you!<br>Your article has been added!</b></h1>
    </div>
</div>
@endsection
@else
<script>
    window.location.href = "{{ url('/confirm') }}";
</script>
@endif
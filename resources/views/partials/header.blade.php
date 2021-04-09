<!-- Header with links -->
<header class="d-flex justify-content-md-end justify-content-center">
    <div class="header-right">
        @if( auth()->check() )
        <a>You're logged as: <b>{{ auth()->user()->username }}</b></a>
        <a href="/logout">Log Out</a>
        @else
        <a id="login-link" href="/login">Login</a>
        <a href="/register">Register</a>
        @endif
    </div>
</header>
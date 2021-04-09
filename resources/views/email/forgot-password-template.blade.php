<h1>Hello {{ $user->username }},</h1>
<p>Please enter the link below in order to reset your password:</p>
<br>
<button class="btn btn-primary"><a href="{{ $link }}">Reset password</a></button>
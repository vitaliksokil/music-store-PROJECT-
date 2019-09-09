<div>
    <h3>Hi,{{$user->name}}</h3>
    <p>To verify your email, click on the link below</p>
    <hr>
    <h2><a target="_blank" href="{{env('APP_URL')}}/verify/{{$user->verification_token}}">Verify email</a></h2>
</div>

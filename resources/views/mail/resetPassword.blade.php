<div>
    <h3>Hi,{{$name}}</h3>
    <p>To reset your password follow the link below</p>
    <hr>
    <h2><a target="_blank" href="{{config('app.url')}}reset-password/{{$token}}-{{$email}}">Reset password</a></h2>
</div>

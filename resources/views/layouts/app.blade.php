
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Music Store') }}</title>
    <link rel="shortcut icon" href="/images/Favicon.jpg" type="image/x-icon">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

<div id="app">

    @include('includes/header')
    <router-view></router-view>
    <vue-progress-bar></vue-progress-bar>
    @include('includes/footer')

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
</script>
</body>
</html>

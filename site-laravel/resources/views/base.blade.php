<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="assets/img/icon.png">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,700">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/jquery.slider.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/index-and-dispatch.css">
    <link rel="stylesheet" href="assets/css/misc.css">

    <link rel="stylesheet" href="/css/app.css">

    <script src="assets/js/thirdparty/jquery-3.3.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <title>@yield('title', 'Чистый город')</title>

    @yield('head')
</head>

<body>
    <x-Header/>

    @yield('body')

    @section('footer')
        <x-Footer/>
    @show

    <script src="assets/js/thirdparty/bootstrap-select.min.js"></script>
    <script src="assets/js/thirdparty/jquery.validate.min.js"></script>
    <script src="assets/js/thirdparty/icheck.min.js"></script>

    @yield('body-scripts')
</body>

</html>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vue</title>

    <!-- Fonts -->


    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">

    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">

</head>

<body>
    <div id="app">
    </div>
</body>
<script type="text/javascript" src="{{mix('js/app.js')}}"></script>

</html>
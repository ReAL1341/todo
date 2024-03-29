<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/index.css')}}">
    <link rel="stylesheet" href="{{asset('/css/input.css')}}">
    <link rel="stylesheet" href="{{asset('/css/list.css')}}">
    <link rel="stylesheet" href="{{asset('/css/channel.css')}}">
    <title>ToDo</title>
</head>
<body>
    <div id="app">
        <app-component></app-component>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
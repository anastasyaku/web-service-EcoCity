@extends('base')

@section('head')
    <link rel="stylesheet" href="assets/css/mark.css">

    <script src="https://api-maps.yandex.ru/2.1/?apikey=35820af1-b1a8-43fd-9a7f-c839a22ee8bb&lang=ru_RU"></script>
    <script src="/assets/js/marks.js"></script>
    <script src="/assets/js/map.js"></script>
    <script src="assets/js/mobile-mark-selection.js"></script>
@endsection

@section('footer')
    <x-Footer :map-elem-id="'#map'"/>
@endsection

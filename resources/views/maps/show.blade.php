@extends('layouts.app')
{{-- @section('styles')
    <link rel="stylesheet" href="{{asset('css/maps.css')}}">
@endsection --}}

@section('content')
    <div id="map-container">
        @csrf
        {{-- <img src="{{$map->map_image_url}}" alt="{{$map->map_name}}"> --}}
    </div>
@endsection

@section('scripts')
    <script>
        let mapUrl = '{{$map->map_image_url}}'
        let mapWidth = {{$map->map_width}}
        let mapHeight = {{$map->map_height}}
    </script>
    <script src="{{ asset('js/maps.js') }}"></script>
@endsection
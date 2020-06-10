@extends('layouts.app')

@section('content')
    <div id="map-container">
        <div class="alert alert-success fixed-top invisible" style="z-index: 10000;" role="alert">
            <h4>Note saved!</h4>
        </div>
        <div id="map-sidebar" class="leaflet-sidebar collapsed">
            <!-- Nav tabs -->
            <div class="leaflet-sidebar-tabs">
                <ul role="tablist"> <!-- top aligned tabs -->
                    <li><a href="#home" role="tab"><i class="fa fa-bars"></i></a></li>
                    <li><a href="#marker" role="tab"><i class="fa fa-map-marker-alt"></i></a></li>
                    <li><a href="#gm" role="tab"><i class="fa fa-user"></i></a></li>
                    <li><a href="#players" role="tab"><i class="fa fa-users"></i></a></li>
                </ul>
                <!-- bottom aligned tabs
                <ul role="tablist">
                    <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
                </ul> -->
            </div>
            <!-- Tab panes -->
            <div class="leaflet-sidebar-content">
                <div class="leaflet-sidebar-pane" id="home">
                    <h1 class="leaflet-sidebar-header">
                        All Markers
                        <div class="leaflet-sidebar-close"><i class="fa fa-caret-left"></i></div>
                    </h1>
                    <div class="list-group list-group-flush">
                        @foreach($markers as $marker)
                            <button type="button" class="list-group-item list-group-item-action marker-button" data-marker-id="{{$marker->id}}">{{$marker->note_title}}</button>
                        @endforeach
                    </div>
                </div>
                <div class="leaflet-sidebar-pane" id="marker">
                    <h1 class="leaflet-sidebar-header mb-4"><span id="note-title" contenteditable="true"></span><div class="leaflet-sidebar-close"><i class="fa fa-caret-left"></i></div></h1>
                    <input id="marker-id" type="hidden" value="${marker.id}">
                    <div id="note-editor"></div>
                    <button id="note-submit" class="mt-3 btn btn-primary btn-block">Submit</button>
                    <button id="new-marker" class="mt-3 btn btn-success btn-block">New Marker</button>
                </div>
                <div class="leaflet-sidebar-pane" id="gm">
                    <h1 class="leaflet-sidebar-header">GM<div class="leaflet-sidebar-close"><i class="fa fa-caret-left"></i></div></h1>
                </div>
                <div class="leaflet-sidebar-pane" id="players">
                    <h1 class="leaflet-sidebar-header">Players<div class="leaflet-sidebar-close"><i class="fa fa-caret-left"></i></div></h1>
                </div>
            </div>
        </div>
        @csrf
    </div>
@endsection

@section('scripts')
    <script>
        let mapUrl = '{!!$map->map_image_url!!}'
        let map_id = {!!$map->id!!}
        let mapWidth = {!!$map->map_width!!}
        let mapHeight = {!!$map->map_height!!}
        let markers = {!!$markers!!}
    </script>
    <script type="module" src="{{ asset('js/maps.js') }}"></script>
@endsection
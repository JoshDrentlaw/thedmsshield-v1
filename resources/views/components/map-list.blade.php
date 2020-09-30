<div class="list-group-item" id="map-{{$map->id}}">
    <div class="row map-row mb-2">
        {{-- NAME AND IMAGE --}}
        <div class="col-sm-6">
            <a class="map-link" href="{{$map->campaign->url}}/maps/{{$map->map_url}}">
                <h4 id="map-name-header-{{$map->id}}">{{$map->map_name}}</h4>
                <img id="{{$map->map_url}}" src="{{$map->map_preview_url}}" alt="{{$map->map_name}}" class="img-thumbnail">
            </a>
        </div>
        @if ($isDm)
            <div class="col-sm-6">
                <div class="row mb-2">
                    <div class="col-12">
                        {{-- CONFIG --}}
                        <button class="btn btn-secondary btn-block config-map" data-map-id="{{$map->id}}" data-map-name="{{$map->map_name}}" data-toggle="modal" data-target="#config-map-modal">Configure</button>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-12">
                        {{-- DELETE --}}
                        <button class="btn btn-danger btn-block delete-map" data-map-id="{{$map->id}}" data-toggle="modal" data-target="#delete-map-modal">Delete</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
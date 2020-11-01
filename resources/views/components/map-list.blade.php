<div class="col mb-4" id="map-{{$map->id}}">
    <div class="card map-row">
        <a class="dmshield-link" href="{{$map->campaign->url}}/maps/{{$map->map_url}}">
            <img id="{{$map->map_url}}" src="{{$map->map_preview_url}}" alt="{{$map->map_name}}" class="card-img-top">
        </a>
        {{-- NAME AND IMAGE --}}
        <div class="card-body">
            <a class="card-title dmshield-link" href="{{$map->campaign->url}}/maps/{{$map->map_url}}">
                <h4 class="mb-0" id="map-name-header-{{$map->id}}">{{$map->map_name}}</h4>
            </a>
        </div>
        @if ($isDm)
            <div class="btn-group-vertical">
                {{-- CONFIG --}}
                <button class="btn btn-secondary btn-block config-map rounded-0" data-map-id="{{$map->id}}" data-map-name="{{$map->map_name}}" data-toggle="modal" data-target="#config-map-modal">Configure</button>
                {{-- DELETE --}}
                <button class="btn btn-danger btn-block delete-map" data-map-id="{{$map->id}}" data-toggle="modal" data-target="#delete-map-modal">Delete</button>
            </div>
        @endif
    </div>
</div>
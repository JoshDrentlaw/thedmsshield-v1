<div class="col mb-4" id="map-{{$map->id}}">
    <div class="card map-row">
        <a class="dmshield-link" href="{{$map->campaign->url}}/maps/{{$map->url}}">
            <img
                id="{{$map->url}}"
                class="card-img-top map-image-thumbnail"
                alt="{{$map->name}}"
                src="{{env('CLOUDINARY_IMG_PATH') . 'c_thumb,h_150/v' . time() . '/' . $map->public_id . '.jpg'}}"
            >
        </a>
        {{-- NAME AND IMAGE --}}
        <div class="card-body">
            <a class="card-title dmshield-link" href="{{$map->campaign->url}}/maps/{{$map->url}}">
                <h4 class="mb-0" id="map-name-header-{{$map->id}}">{{$map->name}}</h4>
            </a>
        </div>
        @if ($isDm)
            <div class="btn-group-vertical">
                {{-- CONFIG --}}
                <button class="btn btn-secondary btn-block config-map rounded-0" data-map-id="{{$map->id}}" data-map-name="{{$map->name}}" data-toggle="modal" data-target="#config-map-modal">Configure</button>
                {{-- DELETE --}}
                <button class="btn btn-danger btn-block delete-map" data-map-id="{{$map->id}}" data-toggle="modal" data-target="#delete-map-modal">Delete</button>
            </div>
        @endif
    </div>
</div>
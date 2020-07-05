<div class="list-group-item" id="map-{{$map->id}}">
    <div class="row map-row mb-2">
        {{-- NAME AND IMAGE --}}
        <div class="col-sm-6">
            <a class="map-link" href="/maps/{{$map->map_url}}">
                <h4 id="map-name-header-{{$map->id}}">{{$map->map_name}}</h4>
                <img id="{{$map->map_url}}" src="{{$map->map_preview_url}}" alt="{{$map->map_name}}" class="img-thumbnail">
            </a>
        </div>
        <div class="col-sm-6">
            <div class="row mb-2">
                <div class="col-12">
                    {{-- CONFIG --}}
                    <button class="btn btn-secondary btn-block config-map" data-map-id="{{$map->id}}" data-map-name="{{$map->map_name}}" data-toggle="modal" data-target="#config-map-modal">Configure</button>
                </div>
                
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    {{-- PLAYERS --}}
                    <button class="btn btn-primary btn-block add-players" data-map-id="{{$map->id}}" data-map-name="{{$map->map_name}}" data-toggle="modal" data-target="#add-players-modal">Add Players</button>
                </div>
                
            </div>
            <div class="row">
                <div class="col-12">
                    {{-- DELETE --}}
                    <button class="btn btn-danger btn-block delete-map" data-map-id="{{$map->id}}" data-toggle="modal" data-target="#delete-map-modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row players-row">
        <div class="col-12">
            <label><strong>Players</strong></label>
            <div class="row">
                @forelse($map->players as $player)
                    @if ($player->pivot->accepted == 1)
                        <div class="col-sm-3">
                            <a class="player-link" href="/profile/{{$player->user->id}}">
                                <figure class="figure">
                                    @if ($player->user->avatar_url_small)
                                        <img src="{{$player->user->avatar_url_small}}" class="mr-3 figure-img rounded" alt="player avater">
                                    @else
                                        <div style="width:64px;height:64px;padding:0.5em;"><i class="w-100 h-100 fa fa-user"></i></div>
                                    @endif
                                    <figcaption class="figure-caption">{{$player->user->name}}</figcaption>
                                </figure>
                            </a>
                        </div>
                    @endif
                @empty
                    <div class="col-sm-3">
                        <p><i>No players...</i></p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<div class="list-group-item" id="campaign-{{$campaign->id}}">
    <div class="row campaign-row mb-2">
        {{-- NAME AND IMAGE --}}
        <div class="col-sm-6">
            <a class="campaign-link" href="/campaigns/{{$campaign->url}}">
                <h4 id="campaign-name-header-{{$campaign->id}}">{{$campaign->name}}</h4>
                @if ($campaign->campaign_preview_url)
                    <img id="{{$campaign->url}}" src="{{$campaign->campaign_preview_url}}" alt="{{$campaign->name}}" class="img-thumbnail">
                @else
                    <div class="bg-dark text-light d-flex justify-content-center align-items-center" style="width:300px;height:195px;font-size:25px;"><span>No image</span></div>
                @endif
            </a>
        </div>
        <div class="col-sm-6">
            <div class="row mb-2">
                <div class="col-12">
                    {{-- CONFIG --}}
                    <button class="btn btn-secondary btn-block config-campaign" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#config-campaign-modal">Configure</button>
                </div>
                
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    {{-- PLAYERS --}}
                    <button class="btn btn-primary btn-block add-players" data-campaign-id="{{$campaign->id}}" data-campaign-name="{{$campaign->name}}" data-toggle="modal" data-target="#add-players-modal">Add Players</button>
                </div>
                
            </div>
            <div class="row">
                <div class="col-12">
                    {{-- DELETE --}}
                    <button class="btn btn-danger btn-block delete-campaign" data-campaign-id="{{$campaign->id}}" data-toggle="modal" data-target="#delete-campaign-modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row players-row">
        <div class="col-12">
            <label><strong>Players</strong></label>
            <div class="row">
                @forelse($campaign->active_players as $player)
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
                @empty
                    <div class="col-sm-3">
                        <p><i>No players...</i></p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
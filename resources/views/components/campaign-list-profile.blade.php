<div class="list-group-item" id="campaign-{{$campaign->id}}">
    <div class="row campaign-row mb-2">
        {{-- NAME AND IMAGE --}}
        <div class="col-sm-12">
            <a class="campaign-link" href="/campaigns/{{$campaign->url}}">
                <h4 id="campaign-name-header-{{$campaign->id}}">{{$campaign->name}}</h4>
                <img id="{{$campaign->url}}" src="{{$campaign->image_url}}" alt="{{$campaign->name}}" class="img-fluid image-thumbnail">
            </a>
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
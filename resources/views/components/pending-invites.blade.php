@forelse($players as $player)
    <a href="/profile/{{$player->id}}" class="list-group-item list-group-item-action">
        <div class="media" style="height:64;">
            @if ($player->user->avatar_url_small)
                <img src="{{$player->user->avatar_url_small}}" alt="Pending player avatar" class="mr-3">
            @else
                <div style="width:64px;height:64px;padding:1em;" class="img-thumbnail mr-3 interactive"><i class="fa fa-user w-100 h-100"></i></div>
            @endif
            <div class="media-body my-auto">
                <h5 class="my-0 interactive">{{$player->user->name}}</h5>
            </div>
        </div>
    </a>
@empty
    <p><i>No pending players...</i></p>
@endforelse
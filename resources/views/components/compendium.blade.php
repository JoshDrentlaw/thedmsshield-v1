<div class="row justify-content-center mb-4">
    <?php
        switch ($path) {
            case 'map':
                $col = 'col-md-12';
                $btnGrpFloat = '';
                $titleInline = '';
                break;
            case 'campaign':
                $col = 'col-md-12';
                $btnGrpFloat = 'float-right';
                $titleInline = 'd-inline-block';
                break;
            default:
                $col = 'col-md-8';
                $btnGrpFloat = 'float-right';
                $titleInline = 'd-inline-block';
                break;
        }
    ?>
    <div class="{{$col}}">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">
                    <i class="fa fa-book"></i>
                    Campaign Compendium
                </h3>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h4 class="mb-4 {{$titleInline}}">
                        <i class="fa fa-users"></i>
                        People & Creatures
                    </h4>
                    <div class="btn-group btn-group-sm {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#peopleDescription" data-toggle="collapse" aria-expanded="false" aria-controls="peopleDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary btn-sm float-right">New person</button>
                        @endif
                    </div>
                    <div class="collapse mt-3" id="peopleDescription">
                        <div class="card card-body">
                            <p>A person or creature is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <h4 class="mb-4 {{$titleInline}}">
                        <i class="fa fa-landmark"></i>
                        Places
                    </h4>
                    <div class="btn-group btn-group-sm {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#placesDescription" data-toggle="collapse" aria-expanded="false" aria-controls="placesDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary" id="new-place-btn">New place</button>
                        @endif
                    </div>
                    <div class="collapse mt-3" id="placesDescription">
                        <div class="card card-body">
                            <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                            @if ($isDm)
                                <p>While in a map, you can turn places into markers by clicking the "Turn place into marker" button.</p>
                            @endif
                        </div>
                    </div>
                    <div class="list-group list-group-flush" id="compendium-places-list">
                        @foreach ($campaign->places as $place)
                            <a class="list-group-item list-group-item-action interactive dmshield-link compendium-place" data-place-id="{{$place->id}}" {{$path === 'map' ? '' : 'href=/campaigns/' . $campaign->url . '/compendium/places/' . $place->url}}>
                                <label>
                                    {{$place->name}}
                                    @if($place->marker)
                                        <i class="fa fa-map-marker-alt"></i>
                                        <small class="text-muted">{{$place->marker->map->name}}</small>
                                    @endif
                                </label>
                            </a>
                        @endforeach
                    </div>
                </li>
                <li class="list-group-item">
                    <h4 class="mb-4 {{$titleInline}}">
                        <i class="fa fa-magic"></i>
                        Things
                    </h4>
                    <div class="btn-group btn-group-sm {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#thingsDescription" data-toggle="collapse" aria-expanded="false" aria-controls="thingsDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary btn-sm float-right">New thing</button>
                        @endif
                    </div>
                    <div class="collapse mt-3" id="thingsDescription">
                        <div class="card card-body">
                            <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <h4 class="mb-4 {{$titleInline}}">
                        <i class="fa fa-lightbulb"></i>
                        Ideas
                    </h4>
                    <div class="btn-group btn-group-sm {{$btnGrpFloat}}">
                        <button class="btn btn-secondary" data-target="#ideasDescription" data-toggle="collapse" aria-expanded="false" aria-controls="ideasDescription">Description</button>
                        @if ($isDm)
                            <button class="btn btn-primary btn-sm float-right">New idea</button>
                        @endif
                    </div>
                    <div class="collapse mt-3" id="ideasDescription">
                        <div class="card card-body">
                            <p>A place is any location in this campaign that is important. It doesn't matter how big or small it is. It could be as small as a plaque or an entire universe.</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
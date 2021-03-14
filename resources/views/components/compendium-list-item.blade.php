<?php
    use App\Debug\Debug;

    switch ($path) {
        case 'map':
            $btnGrp = 'btn-group-vertical';
            $titleInline = '';
            $show = ' show-component';
            break;
        case 'campaign':
            $btnGrp = 'btn-group';
            $titleInline = 'd-inline-block';
            $show = '';
            break;
        default:
            $btnGrp = 'btn-group';
            $titleInline = 'd-inline-block';
            $show = '';
            break;
    }
?>
<li class="list-group-item">
    <div class="mb-2">
        <h4 class="mb-2 {{$titleInline}}">
            {{$header}}
        </h4>
    </div>
    <div class="{{$btnGrp}} btn-group-sm mb-2 compendium-btn-group">
        <a href="/campaigns/{{$campaign->url}}/compendium/{{$itemType}}s" class="btn btn-primary">All {{$itemType}}s</a>
        @if ($isDm)
            <button class="btn btn-success btn-sm new-compendium-item" data-type="{{$itemType}}">New {{$itemType}}</button>
        @endif
        <button class="btn btn-secondary" data-target="#{{$itemType}}Description" data-toggle="collapse" aria-expanded="false" aria-controls="{{$itemType}}Description">Description</button>
    </div>
    <div class="collapse mb-2 compendium-description" id="{{$itemType}}Description">
        <div class="card card-body">
            {{$description}}
        </div>
    </div>
    <div class="list-group list-group-flush compendium-list-group" id="compendium-{{$itemType}}s-list">
        <?php
            $items = $item->sortBy([['name', 'asc']]);
            $markerless = collect([]);
            $maps = collect([]);
            foreach ($items as $item) {
                if ($item->markerless) {
                    $markerless->push($item);
                } else {
                    if (!$maps->has($item->marker->map->name)) {
                        $maps->put($item->marker->map->name, collect([]));
                    }
                    $maps[$item->marker->map->name]->push($item);
                }
            }
            $maps->put('markerless', $markerless);
        ?>
        @forelse($maps as $map)
            @foreach ($map as $item)
                <?php
                    $markerId = '';
                    $mapId = '';
                    if ($item->marker) {
                        $markerId = ' data-marker-id=' . $item->marker->id . '';
                        $mapId = ' data-map-id=' . $item->marker->map->id . '';
                    }
                    $vis = ($isDm || (!$isDm && $item->visible)) ? '' : ' d-none';
                    $url = 'href=/campaigns/' . $campaign->url . "/compendium/{$itemType}s/" . $item->url;
                ?>
                <a class="list-group-item list-group-item-action interactive dmshield-link compendium-{{$itemType}} compendium-item{{$show}}{{$vis}}"{{$markerId}}{{$mapId}} data-{{$itemType}}-id="{{$item->id}}" data-type="{{$itemType}}" {{$path === 'map' ? '' : $url}}>
                    {{$item->name}}
                    @if($item->marker && ($isDm || (!$isDm && $item->marker->visible)))
                        <span class="marker-location">
                            <i class="fa fa-map-marker-alt"></i>
                            <small class="text-muted">{{$item->marker->map->name}}</small>
                        </span>
                    @else
                        @if($path === 'map' && $isDm)
                            <button class="btn btn-success btn-sm float-right to-marker-btn" data-{{$itemType}}-id="{{$item->id}}"><i class="fa fa-map-marker-alt"></i></button>
                        @endif
                    @endif
                </a>
            @endforeach
        @empty
            <p class="mb-0 first-item">Add your first {{$itemType}}!</p>
        @endforelse
    </div>
</li>
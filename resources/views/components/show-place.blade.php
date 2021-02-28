<div id="place-body" class="card card-body">
    @csrf
    <h1 class="card-title mb-4">
        <span class="show-place-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {{$place->name}}
        </span>
        @if($place->marker)
            <br><small class="text-muted d-inline-block mt-3"><i class="fa fa-map-marker-alt mr-2"></i>{{$place->marker->map->name}}</small>
        @endif
    </h1>
    <div class="form-group mb-4">
        @if(!$place->markerless)
            <input id="marker-id" value="{{$place->marker->id}}" type="hidden">
        @endif
        <input id="place-id" value="{{$place->id}}" type="hidden">
        <div class="show-place-editor-container d-none">
            <span>Last updated: <em class="save-time">{{$lastUpdated->format('c')}}</em></span>
            <div class="show-place-body-editor">
                {!!$place->body!!}
            </div>
            <button type="button" class="show-place-change-view-btn btn btn-secondary mt-4">Change view</button>
        </div>

        <div class="show-place-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {!!$place->body!!}
        </div>
    </div>

    @if($isDm && !$place->markerless)
        <div id="marker-options">
            <div class="card">
                <div class="card-body">
                    <label>Marker Icon</label>
                    <select id="marker-icon-select">
                        <?php
                            $marker = new App\Models\Marker;
                        ?>
                        @foreach($marker->place_icons as $icon)
                            <?php
                                $text = Str::title(str_replace('-', ' ', $icon));
                            ?>
                            <option value="{{$icon}}">{{$text}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button id="delete-marker" class="mt-3 btn btn-danger btn-block">Delete Marker</button>
        </div>
    @endif
    @if($isDm && $onMap)
        <button id="show-to-players" class="mt-3 btn btn-info btn-block" data-id="{{$place->id}}" data-type="places">Show to Players</button>
    @endif
</div>

@section('component-scripts')
    @if(!$onMap)
        <script>
            const isDm = {!!$isDm!!}
        </script>
        <script type="module" src="{{ asset('js/show-place.js') . '?' . time() }}"></script>
    @endif
@endsection
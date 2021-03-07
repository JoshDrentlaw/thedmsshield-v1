<div id="compendium-item-container" class="card card-body">
    @csrf
    <input id="item-id" value="{{$item->id}}" type="hidden">
    <input id="item-type" value="{{$itemType}}" type="hidden">
    <h1 class="card-title">
        <span class="show-compendium-item-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            {{$item->name}}
        </span>
        @if($item->marker)
            <?php
                $hideLocation = ' d-none';
                if (!$isDm && $item->marker->visible) {
                    $hideLocation = '';
                }
            ?>
            <br><small id="marker-location" class="text-muted mt-3{{$hideLocation}}"><i class="fa fa-map-marker-alt mr-2"></i>{{$item->marker->map->name}}</small>
        @endif
    </h1>

    <div class="form-group mt-4">
        @if(!$item->markerless)
            <input id="marker-id" value="{{$item->marker->id}}" type="hidden">
        @endif
        <div class="show-compendium-item-editor-container d-none">
            <h3>Notes</h3>
            <span>Last updated: <em class="save-time">{{$lastUpdated->format('c')}}</em></span>
            <div id="all-editor" class="show-compendium-item-body-editor">
                {!!$item->body!!}
            </div>
            @if($isDm)
                <h3 class="mt-3">DM Notes</h3>
                <div id="dm-editor" class="show-compendium-item-dm-note-editor">
                    {!!$item->dm_notes!!}
                </div>
            @endif
            <button type="button" class="show-compendium-item-change-view-btn btn btn-secondary mt-4">Change view</button>
        </div>

        <div class="show-compendium-item-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="<?= $isDm ? 'true' : 'false' ?>">
            <div class="card card-body">
                <h5 class="card-title">Notes</h5>
                <div id="body-content">
                    {!!$item->body!!}
                </div>
            </div>
            @if($isDm)
                <div class="card card-body mt-3">
                    <h5 class="card-title">DM Notes</h5>
                    <div id="dm-note-content">
                        {!!$item->dm_notes!!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@section('component-scripts')
    @if(!$onMap)
        <script>
            const isDm = {!!$isDm!!}
        </script>
        <script type="module" src="{{ asset('js/show-' . $itemType . '.js') . '?' . time() }}"></script>
    @endif
@endsection
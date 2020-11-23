<div id="place-body">
    <h1 class="card-title">
        <span class="show-place-name<?= $isDm ? ' interactive' : '' ?>" contenteditable="true">
            @csrf
            {{$place->name}}
        </span>
        @if($place->marker)
            <small class="text-muted">{{$place->marker->map->name}}</small>
        @endif
    </h1>
    <input id="marker-id" value="{{$place->marker->id}}" type="hidden">
    <input id="place-id" value="{{$place->id}}" type="hidden">
    <div class="show-place-editor-container d-none">
        <span>Last updated: <em class="save-time">{{$lastUpdated->format('c')}}</em></span>
        <div class="show-place-body-editor">
            {!!$place->body!!}
        </div>
        <button type="button" class="show-place-change-view-btn btn btn-secondary mt-4">Change view</button>
    </div>

    <div class="show-place-body-display<?= $isDm ? ' interactive' : '' ?>" contenteditable="false">
        {!!$place->body!!}
    </div>
</div>

@section('component-scripts')
    <script type="module" src="{{ asset('js/show-place.js') . '?' . time() }}"></script>
@endsection
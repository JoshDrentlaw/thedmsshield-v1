<div id="idea-body">
    <h1 class="card-title">
        <span class="<?= $isDm ? 'interactive' : '' ?>" id="show-idea-name" contenteditable="true">
            @csrf
            {{$idea->name}}
        </span>
        @if($idea->marker)
            <small class="text-muted">{{$idea->marker->map->name}}</small>
        @endif
    </h1>
    <div id="show-idea-editor-container" class="d-none">
        <span>Last updated: <em id="show-idea-save-time">{{$lastUpdated->format('c')}}</em></span>
        <div id="show-idea-body-editor" class="">
            {!!$idea->body!!}
        </div>
        <button type="button" id="show-idea-change-view-btn" class="btn btn-secondary mt-4">Change view</button>
    </div>

    <div id="show-idea-body-display" class="<?= $isDm ? 'interactive' : '' ?>" contenteditable="false">
        {!!$idea->body!!}
    </div>
</div>

@section('component-scripts')
    <script type="module" src="{{ asset('js/show-idea.js') . '?' . time() }}"></script>
@endsection
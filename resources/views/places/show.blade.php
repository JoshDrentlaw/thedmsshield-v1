<?php
use App\Debug;
?>

@extends('layouts.app')

@section('content')
<div class="card my-4">
    <div class="card-header">
        <h1>
            <span class="<?= $isDm ? 'interactive' : '' ?>" id="place-name" contenteditable="true">
                @csrf
                {{$place->name}}
            </span>
            @if($place->marker)
                <small class="text-muted">{{$place->marker->map->map_name}}</small>
            @endif
        </h1>
    </div>
    <div class="card-body <?= $isDm ? 'interactive' : '' ?>" id="place-body">
        <div id="editor-container" class="d-none">
            <span>Last updated: <em id="save-time">{{$last_updated->format('c')}}</em></span>
            <div id="body-editor" class="">
                {!!$place->body!!}
            </div>
            <button type="button" id="change-view-btn" class="btn btn-secondary mt-4">Change view</button>
        </div>

        <div id="body-display">
            {!!$place->body!!}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const id = {!!$place->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/places.js') }}"></script>
@endsection
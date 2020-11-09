<?php
use App\Models\Debug;
?>

@extends('layouts.app')

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <x-show-idea :idea="$idea" :is-dm="$isDm" :last-updated="$lastUpdated" />
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const idea_id = {!!$idea->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/ideas.js') . '?' . time() }}"></script>
@endsection
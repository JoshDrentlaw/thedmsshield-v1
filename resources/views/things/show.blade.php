<?php
use App\Models\Debug;
?>

@extends('layouts.app')

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <x-show-thing :thing="$thing" :is-dm="$isDm" :last-updated="$lastUpdated" />
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const thing_id = {!!$thing->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/things.js') . '?' . time() }}"></script>
@endsection
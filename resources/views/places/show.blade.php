<?php
use App\Debug\Debug;
?>

@extends('layouts.app')

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <x-show-place :place="$place" :is-dm="$isDm" :last-updated="$lastUpdated" />
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const place_id = {!!$place->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/places.js') . '?' . time() }}"></script>
@endsection
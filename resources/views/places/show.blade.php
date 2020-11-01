<?php
use App\Debug;
?>

@extends('layouts.app')

@section('content')
<x-show-place :place="$place" :is-dm="$isDm" :last-updated="$lastUpdated" />
@endsection

@section('scripts')
    <script>
        const place_id = {!!$place->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/places.js') . '?' . time() }}"></script>
@endsection
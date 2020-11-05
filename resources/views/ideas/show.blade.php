<?php
use App\Debug;
?>

@extends('layouts.app')

@section('content')
<x-show-idea :idea="$idea" :is-dm="$isDm" :last-updated="$lastUpdated" />
@endsection

@section('scripts')
    <script>
        const idea_id = {!!$idea->id!!}
        const campaign_id = {!!$campaign->id!!}
        const uri = "{!!$uri!!}"
    </script>
    <script type="module" src="{{ asset('js/ideas.js') . '?' . time() }}"></script>
@endsection
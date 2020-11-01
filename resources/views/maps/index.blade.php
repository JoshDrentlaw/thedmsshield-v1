@extends('layouts.app')

@section('content')
<div class="jumbotron text-center">
    <h1 class="display-4">Maps</h1>
</div>
    @if(count($maps) > 1)
        <ul class="list-unstyled col-sm-8 offset-sm-2">
            @foreach($maps as $map)
                <a class="mx-auto dmshield-link" href="maps/{{$map->map_url}}">
                    <li class="media mb-4">
                        <img src="{{$map->map_preview_url}}" alt="{{$map->map_name}}" class="img-thumbnail">
                        <div class="media-body ml-4 d-flex" style="height:205px;">
                            <h2 class="h2 my-0 align-self-center">{{$map->map_name}}</h5>
                        </div>
                    </li>
                </a>
            @endforeach
        </ul>
    @else
        <p>No posts found</p>
    @endif
@endsection
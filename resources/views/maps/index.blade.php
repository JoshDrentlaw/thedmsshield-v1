@extends('layout.app')

@section('content')
<div class="jumbotron text-center">
    <h1 class="display-4">Maps</h1>
</div>
    @if(count($maps) > 1)
        <ul class="list-unstyled col-sm-8 offset-sm-2">
            @foreach($maps as $map)
                <li class="media mb-4">
                    <a class="mx-auto" href="maps/{{$map->id}}">
                        <div class="row align-items-center">
                            <div class="col-lg-7">
                                <img src="{{$map->map_preview_url}}" alt="{{$map->map_name}}" class="">
                            </div>
                            <div class="col-lg-5">
                                <div class="media-body">
                                    <h2 class="h2 my-0">{{$map->map_name}}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No posts found</p>
    @endif
@endsection
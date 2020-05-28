@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-stripped">
                        <tr>
                            <th>Map</th>
                            <th></th>
                            <th></th>
                        </tr>
                        @foreach($maps as $map)
                            <tr>
                                <th>{{$map->map_name}}</th>
                                <th><a href="/maps/{{$map->id}}/edit" class="btn btn-secondary">Edit</a></th>
                                <th></th>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

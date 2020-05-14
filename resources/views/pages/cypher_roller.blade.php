@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">Cypher System Die Roller</h1>
    </div>
    <div class="border border-dark bg-danger p-4 rounded w-100">
        <form class="mx-auto w-50">
            <div class="float-right d-inline w-50">
                <div class="form-group">
                    <label for="difficulty-input" class="d-block"><h2 class="display-4 text-right text-light">Difficulty</h2></label>
                    <input type="number" class="form-control-lg float-right" value="0" min="0" max="10">
                </div>
            </div>
            <div class="row w-50">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="asset-select"><h3 class="font-weight-bold text-light">Assets:</h3></label>
                        <select name="asset" id="asset-select" class="form-control">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="skills-select"><h3 class="font-weight-bold text-light">Skills:</h3></label>
                        <select name="skills" id="skills-select" class="form-control">
                            <option value="0">None</option>
                            <option value="1">Trained</option>
                            <option value="2">2 Trained</option>
                            <option value="2">Specialized</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row w-50">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="effort-select"><h3 class="font-weight-bold text-light">Effort:</h3></label>
                        <select name="effort" id="effort-select" class="form-control">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="edge-select"><h3 class="font-weight-bold text-light">Edge:</h3></label>
                        <select name="edge" id="edge-select" class="form-control">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="cost-counter"><h3 class="font-weight-bold text-light">Cost:</h3></label>
                        <h2 class="text-light text-right h2">0</h2>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
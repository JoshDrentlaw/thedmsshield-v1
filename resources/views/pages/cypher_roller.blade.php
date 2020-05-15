@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1 class="display-4">Cypher System Die Roller</h1>
    </div>
    <div class="border border-dark bg-danger p-4 rounded w-100">
        <form class="mx-auto w-75">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
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
                    <div class="row">
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
                                <h2 id="effort-cost" class="text-light text-right h2">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="difficulty-input" class="d-block"><h2 class="display-4 text-right text-light">Difficulty</h2></label>
                        <div class="row">
                            <div class="col-sm-2 offset-sm-6">
                                <span id="final-difficulty" class="float-right text-light mr-5" style="font-size:xxx-large;line-height:1;">0</span>
                            </div>
                            <div class="col-sm-4">
                                <select id="difficulty-input" class="form-control-lg float-right">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/cypher_roller.js') }}"></script>
@endsection
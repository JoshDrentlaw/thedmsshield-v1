<?php
    $uri = $_SERVER['REQUEST_URI'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/">{{config('app.name', 'The DM\'s Shield')}}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="nav-item {{$uri === '/' ? 'active' : ''}}"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item {{$uri === '/cypher_calculator' ? 'active' : ''}}"><a class="nav-link" href="/cypher_calculator">Cypher Calculator</a></li>
                <li class="nav-item {{$uri === '/maps' ? 'active' : ''}}"><a class="nav-link" href="/maps">Maps</a></li>
            </ul>
        </div>
    </div>
</nav>
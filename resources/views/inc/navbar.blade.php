<?php
    use App\Debug\Debug;
    use App\Models\Campaign;
    use App\Models\Map;

    $uri = $_SERVER['REQUEST_URI'];
    $campaign = false;
    $mapUrl = false;
    $split = explode('/', $uri);
    if ($split[1] === 'campaigns') {
        $campaignUrl = $split[2];
        $campaign = Campaign::firstWhere('url', $campaignUrl);
        if (isset($split[3]) && $split[3] === 'maps' && isset($split[4])) {
            $mapUrl = $split[4];
        }
    }
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
                @auth
                    <li class="nav-item {{$uri === '/dashboard' ? 'active' : ''}}">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    @if($campaign)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="campaign-link-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$campaign->name}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="campaign-link-dropdown">
                                <a class="dropdown-item" href="/campaigns/{{$campaign->url}}">Campaign Dashboard</a>
                                <div class="dropdown-divider"></div>
                                @if($campaign->maps->count() >= 1)
                                    <h6 class="dropdown-header dms-navbar-dropdown-header">Campaign Maps</h6>
                                    @foreach($campaign->maps as $map)
                                        <?php
                                            $active = $mapUrl === $map->url ? ' active' : '';
                                        ?>
                                        <a class="dropdown-item{{$active}}" href="/campaigns/{{$campaign->url}}/maps/{{$map->url}}">
                                            {{$map->name}}
                                            @if($active)
                                                <i class="fa fa-user ml-2"></i>
                                            @endif
                                        </a>
                                    @endforeach
                                    <div class="dropdown-divider"></div>
                                @endif
                                <h6 class="dropdown-header dms-navbar-dropdown-header">Campaign Compendium</h6>
                                <a class="dropdown-item" href="/campaigns/{{$campaign->url}}/compendium/creatures">Creatures</a>
                                <a class="dropdown-item" href="/campaigns/{{$campaign->url}}/compendium/places">Places</a>
                                <a class="dropdown-item" href="/campaigns/{{$campaign->url}}/compendium/things">Things</a>
                            </div>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->username }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        {{-- <a class="dropdown-item" href="{{ route('account') }}">
                            {{ __('Account') }}
                        </a> --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
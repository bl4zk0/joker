<nav class="navbar navbar-expand-md navbar-dark">
    <a class="navbar-brand" href="/"><h3>{{ config('app.name', 'mojokre.dev') }}</h3></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link {{ Request::path() === '/' ? 'active' : '' }}" href="/">მთავარი<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::path() === 'contact' ? 'active' : '' }}" href="/contact">კავშირი</a>
            </li>
            @auth
                <li class="ml-3 nav-item dropdown">
                    <a class="nav-avatar" href="#" data-toggle="dropdown" aria-expanded="false">
                        <img class="border rounded-circle g_avatar" src="{{ Auth::user()->avatar_url }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right bg-success">
                        <a class="dropdown-item text-white" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endauth
        </ul>
    </div>
</nav>

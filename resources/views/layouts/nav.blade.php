<nav class="navbar navbar-expand-md navbar-dark">
    <a class="navbar-brand" href="/"><h3>{{ config('app.name', 'Joker') }}</h3></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <a class="badge badge-light" href="/{{ Request::path() === '/' ? '' : Request::path() }}?lang={{ App::getLocale() == 'en' ? 'ka' : 'en' }}" style="font-size:20px;">
        {{ App::getLocale() == 'en' ? '🇬🇪' : '🇺🇸' }}
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link {{ Request::path() === '/' ? 'active' : '' }}" href="/">@lang('Home')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::path() === 'contact' ? 'active' : '' }}" href="{{ route('contact') }}">@lang('Contact')</a>
            </li>
            @auth
                <li class="ml-3 nav-item dropdown">
                    <a class="nav-avatar" href="#" data-toggle="dropdown" aria-expanded="false">
                        <img class="border rounded-circle g_avatar"
                             src="{{ Auth::user()->avatar_url }}"
                             title="{{ Auth::user()->username }}"
                             alt="{{ Auth::user()->username }}_avatar">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right bg-success">
                        <a class="dropdown-item text-white" href="/user/{{ Auth::id() }}">
                            <i class="fas fa-user"></i> @lang('Profile')
                        </a>
                        <a class="dropdown-item text-white" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> @lang('Sign out')
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

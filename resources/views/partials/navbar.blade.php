<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/home">NOIR</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'home' ? 'active' : '' }}" aria-current="page"
                        href="/home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'game' ? 'active' : '' }}" href="/game">Game</a>
                </li>

            </ul>


            @auth
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="/cart/{{ auth()->user()->username }}" class="btn btn-primary">
                            <i class="bi bi-cart-fill"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $active === 'top_up' ? 'active' : '' }}" href="/top_up">Top Up</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Welcome back, {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-arrow-up-right-square"></i>
                                        Logout</button>
                                </form>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <h3>
                            @if(auth()->user()->points)
                                {{ auth()->user()->points }} POINT
                            @else
                                0 POINT
                            @endif
                        </h3>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ $active === 'login' ? 'active' : '' }}" href="/login"><i
                                class="bi bi-box-arrow-in-right"></i>LOGIN</a>
                    </li>
                </ul>
            @endauth

            {{-- <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form> --}}
                    <a class="nav-link {{ $active === "home" ? 'active' : '' }}" aria-current="page" href="/home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === "game" ? 'active' : '' }}" href="/game">Game</a>
                </li>
            </ul>

            {{-- @auth
            <ul class="navbar-nav me-auto">
                <!-- Shopping Cart Button -->
                <li class="nav-item">
                    <a href="/cart/{{ auth()->user()->username }}" class="btn btn-primary">
                        <i class="bi bi-cart-fill"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome back, {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Add your dropdown menu items here -->
                        <li>
                            <a href="/role/request" style="text-decoration: none">
                                <button type="" class="dropdown-item">
                                    <i class="bi bi-arrow-up-right-square"></i> Request
                                </button>
                            </a>
                        </li>
                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-arrow-up-right-square"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @else
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $active === "login" ? 'active' : '' }}" href="/login">
                        <i class="bi bi-box-arrow-in-right"></i> LOGIN
                    </a>
                </li>
            </ul>
            @endauth --}}
        </div>
    </div>
</nav>

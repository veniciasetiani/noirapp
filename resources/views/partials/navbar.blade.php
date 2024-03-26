<nav class="navbar navbar-expand-lg navbar-dark bg-dark position-sticky top-0">
    <div class="container-fluid ps-3 d-flex">
        <div class="d-flex align-items-center justify-content-center px-lg-3">
            <a class="navbar-brand text-center mx-0" href="/home">NOIR</a>
        </div>
        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex w-100 justify-content-lg-between flex-column flex-lg-row">
                <ul class="navbar-nav ms-2">
                    <li class="nav-item mx-1">
                        <a class="nav-link {{ $active === 'home' ? 'active' : '' }} nb-label" aria-current="page"
                            href="/home">Home</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link {{ $active === 'game' ? 'active' : '' }} nb-label" href="/game">Game</a>
                    </li>

                </ul>


                @auth
                <ul
                    class="navbar-nav d-flex flex-column flex-lg-row justify-items-center align-items-start align-items-lg-center ps-2 h-100">

                    <li class="nav-item d-flex align-items-center mb-2 mb-lg-0 me-lg-2 btn">
                        <a class="nb-label position-relative" href="/chatify">
                            <i class="bi bi-chat-fill" style="color: white"></i>
                            <span id="chatNotificationBadge" class="badge bg-primary rounded-pill position-absolute top-0 start-100 translate-middle ms-1">
                                0
                            </span>
                        </a>
                    </li>


                    <li class="nav-item d-flex align-items-center mb-2 mb-lg-0 me-lg-2 btn">
                        <a href="/cart/{{ auth()->user()->username }}">
                            <i class="bi bi-cart-fill" style="color: white"></i>
                        </a>
                    </li>

                    <li class="nav-item d-flex align-items-center me-lg-2 btn">
                        <img src="/img/gatcha.png" style="height:1.25rem" alt="" class="me-2" />
                        <a href="/top_up" style="text-decoration: none; color: white" class="me-2">
                            @if (auth()->user()->points)
                            {{ auth()->user()->points }} POINT
                            @else
                            0 POINT
                            @endif
                        </a>
                        <a class="{{ $active === 'top_up' ? 'active' : '' }}" href="/top_up">
                            <i class="bi bi-plus-circle-fill" style="color: white"></i>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Welcome back, {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu position-absolute">
                            @if (in_array(auth()->user()->role_id, [1, 2]))
                            <!-- Periksa role_id -->
                            <li>
                                <a href="/order-request" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-arrow-down-left-square"></i> Order Request
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a href="/transactions" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-cart-check"></i> Order history
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a href="/usertransaction" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-clipboard2-check"></i> Your Transactions
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a href="/withdrawal" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-cash-coin"></i> Gatcha withdrawal
                                    </button>
                                </a>
                            </li>
                            {{-- <li>
                                <a href="/updatesingleuser" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-arrow-up-right-square"></i> Edit Displayed Item
                                    </button></a>
                            </li> --}}
                            <li>
                                <a href="/editavailabletimes" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-arrow-down-left-square"></i> Edit Schedule
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a href="/sellerschedule" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-arrow-up-right-square"></i> Schedule
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a href="/history" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-clock-history"></i> History
                                    </button>
                                </a>
                            </li>


                            @endif
                            @if (auth()->user()->role_id == 4)
                            <li>
                                <a href="/usertransaction" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-clipboard2-check"></i> Your Transactions
                                    </button>
                                </a>
                            </li>
                            <li>
                                <a href="/history" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-clock-history"></i> History
                                    </button>
                                </a>
                            </li>

                            {{-- <li>
                                <a href="/userschedule" style="text-decoration: none">
                                    <button type="button" class="dropdown-item">
                                        <i class="bi bi-arrow-up-right-square"></i> Schedule
                                    </button>
                                </a>
                            </li> --}}
                            @endif
                            <li>
                                <a href="/role/request" style="text-decoration: none">
                                    <button type="" class="dropdown-item">
                                        <i class="bi bi-file-person"></i> Request Role
                                    </button>
                                </a>
                            </li>

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
                </ul>
                @else
                <ul class="navbar-nav mt-2 mt-lg-0 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ $active === 'login' ? 'active' : '' }} btn btn-secondary px-2 w-100"
                            href="/login">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
                        </a>
                    </li>
                </ul>
                @endauth

            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
           function updateNotificationCount(count) {
                var notificationBadge = document.getElementById('chatNotificationBadge');

                notificationBadge.innerText = count;
            }

            function fetchAndSetUnreadCount() {
                $.get('/getUnreadMessagesCount', function(data) {
                    updateNotificationCount(data.unreadCount);

                    setTimeout(fetchAndSetUnreadCount, 0);
                });
            }

            fetchAndSetUnreadCount();
        </script>
</nav>

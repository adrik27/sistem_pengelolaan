<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <p class="tx-12 text-muted">{{ Auth::user()->email }} <br> <span class="text-center d-block">
                            ({{ Auth::user()->Jabatan->nama }} - {{ Auth::user()->Department->nama }}) </span></p>
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="text-center">
                            <p class="tx-16 fw-bolder">{{ Auth::user()->nama }}</p>
                            <p class="tx-12 text-muted">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li class="dropdown-item py-2">
                            <a class="text-body bg-transparent border-0 ms-0" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal" style="cursor: pointer">
                                <i class="me-2 icon-md" data-feather="key"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <form id="logoutForm" action="{{ url('/logout') }}" method="post">
                            @csrf
                            <li class="dropdown-item py-2">
                                <button type="submit" form="logoutForm" class="text-body ms-0 bg-transparent border-0"
                                    onclick="logoutForm(this)">
                                    <i class="me-2 icon-md" data-feather="log-out"></i>
                                    <span>Log Out</span>
                                </button>
                            </li>
                        </form>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

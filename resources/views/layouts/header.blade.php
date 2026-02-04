<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <!-- Sidebar toggle for mobile -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                <i class="ti-menu ti-close"></i>
            </a>
            
            <!-- Logo Section -->
            <div class="navbar-brand">
                <a href="{{ route('dashboard') }}" class="logo">
                    <!-- Logo icon -->
                    <b class="logo-icon">
                        <span class="text-white mx-3">FAJAR JAYA</span>
                    </b>
                </a>
                <!-- Sidebar toggler for desktop -->
                <a class="sidebartoggler d-none d-md-block" href="javascript:void(0)" data-sidebartype="mini-sidebar">
                    <i class="mdi mdi-toggle-switch mdi-toggle-switch-off font-20"></i>
                </a>
            </div>
            
            <!-- Mobile menu toggle -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ti-more"></i>
            </a>
        </div>
        
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- Left side navigation -->
            <ul class="navbar-nav me-auto">
                <!-- Search Box -->
                <li class="nav-item search-box">
                    <a class="nav-link waves-effect waves-dark" href="javascript:void(0)" id="searchToggle">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-magnify font-20 me-1"></i>
                            <div class="ms-1 d-none d-sm-block">
                                <span>Search</span>
                            </div>
                        </div>
                    </a>
                    <form class="app-search position-absolute" id="searchForm" style="display: none;">
                        <input type="text" class="form-control" placeholder="Cari data..." id="searchInput">
                        <a class="srh-btn" id="searchClose">
                            <i class="ti-close"></i>
                        </a>
                    </form>
                </li>
                
                <!-- Current time display -->
                <li class="nav-item d-none d-lg-block">
                    <div class="nav-link">
                        <small class="text-muted">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            <span id="currentTime"></span>
                        </small>
                    </div>
                </li>
            </ul>
            
            <!-- Right side navigation -->
            <ul class="navbar-nav ms-auto">
                <!-- Notifications -->
                <!-- <li class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" 
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-bell font-20"></i>
                        <span class="badge bg-danger rounded-pill position-absolute" style="top: 8px; right: 8px; font-size: 10px;">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end animated fadeIn">
                        <div class="dropdown-header">
                            <h6 class="mb-0">Notifikasi</h6>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="mdi mdi-alert-circle text-warning me-2"></i>
                            Stok gas menipis
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="mdi mdi-information text-info me-2"></i>
                            Laporan bulanan siap
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-center">Lihat semua notifikasi</a>
                    </div>
                </li> -->
                
                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    @php
                        // Function untuk generate warna berdasarkan nama
                        if (!function_exists('stringToColor')) {
                            function stringToColor($string) {
                                $colors = [
                                    '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
                                    '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
                                ];
                                return $colors[abs(crc32($string)) % count($colors)];
                            }
                        }
                        
                        // Ambil data user yang sedang login
                        $currentUser = Auth::user();
                        
                        // Generate warna dan inisial
                        $userColor = stringToColor($currentUser->name);
                        $userName = $currentUser->name ?? 'User';
                        $userEmail = $currentUser->email ?? '';
                        $userRole = $currentUser->role ?? 'user';
                        
                        // Generate inisial dari nama
                        $nameWords = explode(' ', trim($userName));
                        $initials = '';
                        foreach ($nameWords as $word) {
                            if (!empty($word)) {
                                $initials .= strtoupper(substr($word, 0, 1));
                                if (strlen($initials) >= 2) break;
                            }
                        }
                        if (empty($initials)) {
                            $initials = strtoupper(substr($userName, 0, 2));
                        }
                    @endphp
                    
                    <a class="nav-link dropdown-toggle waves-effect waves-dark " href="#"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-circle" style="background-color: {{ $userColor }}">
                            <span class="initials">{{ $initials }}</span>
                        </div>
                        {{-- <span class="ms-2 font-medium d-none d-sm-inline-block text-black-50">
                            {{ $userName }} 
                            <i class="mdi mdi-chevron-down ms-1"></i>
                        </span> --}}
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-end user-dd animated fadeIn">
                        <!-- User Info Header -->
                        <div class="d-flex align-items-center p-3 bg-primary text-white mb-2">
                            <div class="avatar-circle-large me-3" style="background-color: {{ $userColor }}">
                                <span class="initials-large">{{ $initials }}</span>
                            </div>
                            <div>
                                <h6 class="mb-0 text-white">{{ $userName }}</h6>
                                <small class="opacity-75">{{ $userEmail }}</small>
                                <br>
                                <small class="opacity-75">
                                    <i class="mdi mdi-account-circle me-1"></i>{{ ucfirst($userRole) }}
                                </small>
                            </div>
                        </div>
                        
                        <!-- Profile Menu Items -->
                        @if(Route::has('users.show'))
                        <a class="dropdown-item" href="{{ route('users.show', $currentUser->id) }}">
                            <i class="mdi mdi-account me-2"></i> Profil Saya
                        </a>
                        @endif
                        
                        @if(Route::has('users.edit'))
                        <a class="dropdown-item" href="{{ route('users.edit', $currentUser->id) }}">
                            <i class="mdi mdi-settings me-2"></i> Pengaturan
                        </a>
                        @endif
                        
                        <div class="dropdown-divider"></div>
                        
                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" id="logoutForm" class="mb-0">
                            @csrf
                            <button class="dropdown-item text-danger" type="button" id="btnLogout">
                                <i class="mdi mdi-power me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<style>
/* Avatar Styles */
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
    margin-top: 30%;
    border: 2px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.avatar-circle-large {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    /* margin-top: 30%; */
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.initials {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    letter-spacing: 1px;
}

.initials-large {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    letter-spacing: 1px;
}

/* Search Box Styles */
.search-box .app-search {
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    padding: 10px;
    min-width: 300px;
}

.search-box .app-search input {
    border: none;
    outline: none;
    width: 100%;
    padding-right: 30px;
}

.search-box .srh-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #999;
}

/* Notification Badge */
.nav-item .badge {
    font-size: 9px;
    padding: 2px 5px;
}

/* Dropdown Improvements */
.dropdown-menu {
    border: none;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.user-dd {
    min-width: 280px;
}

.dropdown-item {
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

/* Responsive Improvements */
@media (max-width: 768px) {
    .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }
    
    .search-box .app-search {
        min-width: 250px;
    }
}

/* Animation Classes */
.fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Logo hover effect */
.navbar-brand .logo:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* User name text styling */
.pro-pic .font-medium {
    font-weight: 500;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchToggle = document.getElementById('searchToggle');
    const searchForm = document.getElementById('searchForm');
    const searchClose = document.getElementById('searchClose');
    const searchInput = document.getElementById('searchInput');
    
    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchForm.style.display = searchForm.style.display === 'none' ? 'block' : 'none';
            if (searchForm.style.display === 'block') {
                searchInput.focus();
            }
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', function(e) {
                e.preventDefault();
                searchForm.style.display = 'none';
                searchInput.value = '';
            });
        }
        
        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchToggle.contains(e.target) && !searchForm.contains(e.target)) {
                searchForm.style.display = 'none';
            }
        });
    }
    
    // Current time display
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        const currentTimeElement = document.getElementById('currentTime');
        if (currentTimeElement) {
            currentTimeElement.textContent = timeString;
        }
    }
    
    updateTime();
    setInterval(updateTime, 60000); // Update every minute
    
    // Debug: Log user info to console
    console.log('User authenticated:', @json(Auth::check()));
    @if(Auth::check())
    console.log('User name:', @json(Auth::user()->name));
    console.log('User email:', @json(Auth::user()->email));
    console.log('User role:', @json(Auth::user()->role));
    @endif
});
</script>

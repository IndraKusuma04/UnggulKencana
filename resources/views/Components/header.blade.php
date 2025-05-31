<div class="header">

    @php
        $role = \App\Models\Role::find(auth()->user()->role_id); // Ambil data role berdasarkan role_id pengguna
        $prefix = '';

        // Cek apakah role ditemukan dan statusnya aktif (status 1 untuk aktif)
        if ($role && $role->status == 1) {
            // Mengubah nilai role menjadi huruf kecil
            $prefix = strtolower($role->role); // Set prefix sesuai dengan role jika status aktif dan ubah ke huruf kecil
        }
    @endphp

    <div class="header-left active">
        <a href="/{{ $prefix }}/dashboard" class="logo logo-normal">
            <img src="{{ asset('assets') }}/img/logo.png" alt>
        </a>
        <a href="/{{ $prefix }}/dashboard" class="logo logo-white">
            <img src="{{ asset('assets') }}/img/logo-white.png" alt>
        </a>
        <a href="/{{ $prefix }}/dashboard" class="logo-small">
            <img src="{{ asset('assets') }}/img/favicon.png" alt>
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">

        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#" class="dropdown">
                    <div class="searchinputs dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown"
                        data-bs-auto-close="false">
                        <input type="text" id="search-input" placeholder="Search">
                        <div class="search-addon">
                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                        </div>
                    </div>
                    <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">
                        <div class="search-info">
                            <h6><span><i data-feather="search" class="feather-16"></i></span>Pencarian Menu
                            </h6>
                            <ul class="search-tags">
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>
        <li class="nav-item nav-item-box">
            <a href="email.html">
                <i data-feather="mail"></i>
                <span class="badge rounded-pill">1</span>
            </a>
        </li>

        <li class="nav-item dropdown nav-item-box">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <i data-feather="bell"></i><span class="badge rounded-pill">2</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt src="{{ asset('assets') }}/img/profiles/avatar-02.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">John Doe</span> added
                                            new task <span class="noti-title">Patient appointment
                                                booking</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">4 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt src="{{ asset('assets') }}/img/profiles/avatar-03.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Tarah
                                                Shropshire</span>
                                            changed the task name <span class="noti-title">Appointment booking
                                                with payment gateway</span></p>
                                        <p class="noti-time"><span class="notification-time">6 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt src="{{ asset('assets') }}/img/profiles/avatar-06.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Misty Tison</span>
                                            added <span class="noti-title">Domenic Houston</span> and <span
                                                class="noti-title">Claire Mapes</span> to project <span
                                                class="noti-title">Doctor available module</span></p>
                                        <p class="noti-time"><span class="notification-time">8 mins ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt src="{{ asset('assets') }}/img/profiles/avatar-17.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Rolland Webber</span>
                                            completed task <span class="noti-title">Patient and Doctor video
                                                conferencing</span></p>
                                        <p class="noti-time"><span class="notification-time">12 mins
                                                ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt src="{{ asset('assets') }}/img/profiles/avatar-13.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">Bernardo
                                                Galaviz</span>
                                            added new task <span class="noti-title">Private chat module</span>
                                        </p>
                                        <p class="noti-time"><span class="notification-time">2 days ago</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="activities.html">View all Notifications</a>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ asset('storage/avatar/' . Auth::user()->pegawai->image_pegawai) }}"
                            alt="avatar" class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ session('nama') }}</span>
                        <span class="user-role">{{ session('jabatan') }}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            <img src="{{ asset('storage/avatar/' . Auth::user()->pegawai->image_pegawai) }}"
                                alt="avatar" class="img-fluid">
                            <span class="status online"></span>
                        </span>
                        <div class="profilesets">
                            <h6>{{ session('nama') }}</h6>
                            <h5>{{ session('jabatan') }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="/admin/profile"> <i class="me-2" data-feather="user"></i>
                        My
                        Profile</a>
                    <a class="dropdown-item" href="general-settings.html"><i class="me-2"
                            data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="/logout"><img
                            src="{{ asset('assets') }}/img/icons/log-out.svg" class="me-2"
                            alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>


    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="/admin/profile">My Profile</a>
            <a class="dropdown-item" href="general-settings.html">Settings</a>
            <a class="dropdown-item" href="/logout">Logout</a>
        </div>
    </div>

</div>
<script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            let query = $(this).val().toLowerCase();
            let dropdown = $('.search-dropdown .search-tags');
            dropdown.empty();

            if (query.length === 0) {
                dropdown.append('<li><a href="#">Ketik untuk mencari menu...</a></li>');
                return;
            }

            let matches = [];

            $('#sidebar a[data-menu-title]').each(function() {
                let title = $(this).data('menu-title').toLowerCase();
                let href = $(this).attr('href');
                if (title.includes(query)) {
                    matches.push(`<li><a href="${href}">${title}</a></li>`);
                }
            });

            if (matches.length > 0) {
                dropdown.append(matches.join(''));
            } else {
                dropdown.append('<li><a href="#">Tidak ditemukan</a></li>');
            }
        });

        // Clear input
        $('.search-addon i').on('click', function() {
            $('#search-input').val('').trigger('input');
        });
    });
</script>

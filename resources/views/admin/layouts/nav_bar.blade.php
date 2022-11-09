<div class="header">
    <!-- Logo -->
    <div class="header-left">
        <a href="{{route('luckywheel.index')}}" taget="_blank" class="logo">
            <img src="cdn/assets/img/logo.png" alt="Logo">
        </a>
        <a href="{{route('luckywheel.index')}}" class="logo logo-small">
            <img src="cdn/assets/img/logo-small.png" alt="Logo" width="30" height="30">
        </a>
        <a href="javascript:void(0);" id="toggle_btn">
            <i class="feather-chevrons-left"></i>
        </a>
    </div>
    <!-- /Logo -->

    <!-- Mobile Menu Toggle -->
    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>
    <!-- /Mobile Menu Toggle -->

    <!-- Header Menu -->
    <ul class="nav nav-tabs user-menu">
        <!-- Flag -->
        <li class="nav-item">
            <a id="dark-mode-toggle" class="dark-mode-toggle">
                <i class="feather-sun light-mode"></i><i class="feather-moon dark-mode"></i>
            </a>
        </li>
        <!-- /Flag -->
        <!-- User Menu -->
        <li class="nav-item dropdown main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
							<span class="user-img">
								<img src="cdn/assets/img/profiles/avatar-01.jpg" alt="">
								<span class="status online"></span>
							</span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="cdn/assets/img/profiles/avatar-01.jpg" alt="User Image"
                             class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{Auth::user()->name}}</h6>
{{--                        <p class="text-muted mb-0">{{Auth::user()->money}}</p>--}}
                    </div>
                </div>
{{--                <a class="dropdown-item" href="profile.html"><i class="feather-user me-1"></i> My Profile</a>--}}
{{--                <a class="dropdown-item" href="profile.html"><i class="feather-edit me-1"></i> Edit Profile</a>--}}
{{--                <a class="dropdown-item" href="account-settings.html"><i class="feather-sliders me-1"></i> Account--}}
{{--                    Settings</a>--}}
                <hr class="my-0 ms-2 me-2">
                <a class="dropdown-item" href="{{route('logOut')}}"><i class="feather-log-out me-1"></i> Đăng xuất</a>
            </div>
        </li>
        <!-- /User Menu -->

    </ul>
    <!-- /Header Menu -->

</div>

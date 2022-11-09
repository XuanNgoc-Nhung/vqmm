<ul>
    <li class="menu-title"><span>Main</span></li>

    <li class="{{ Request::routeIs('admin.getHome') ? 'active' : '' }}">
        <a href="{{route('admin.getHome')}}"><i class="feather-grid"></i> <span>Tổng quan</span></a>
    </li>
    <li class="{{ Request::routeIs('admin.quanLyPhanThuong') ? 'active' : '' }}">
        <a href="{{route('admin.quanLyPhanThuong')}}"><i class="feather-calendar"></i> <span>Quản lý phần thưởng</span></a>
    </li>
    <li class="{{ Request::routeIs('admin.lichSu') ? 'active' : '' }}">
        <a href="{{route('admin.lichSu')}}"><i class="feather-calendar"></i> <span>Lịch sử</span></a>
    </li>
</ul>

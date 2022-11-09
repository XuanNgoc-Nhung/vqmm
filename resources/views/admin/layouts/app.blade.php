<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    @yield('title')
    @include('admin.layouts.link_css')

</head>
<body>
<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Header -->
    @include('admin.layouts.nav_bar')
    <!-- /Header -->
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                @include('admin.layouts.menu')
            </div>
        </div>
    </div>
    <!-- /Sidebar -->

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid pb-0">
            @yield('content')
        </div>
    </div>
    <!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->
@include('admin.layouts.link_js')

@yield('js_location')
</body>
</html>

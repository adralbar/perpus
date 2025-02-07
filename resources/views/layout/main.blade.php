<!DOCTYPE html>
<html lang="en">

<head>
    @yield('style')
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpus</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/bootstrap-icons/bootstrap-icons.css') }}">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> --}}


    <!-- Tempusdominus Bootstrap 4 -->
    <link
        rel="stylesheet"href="{{ asset('/lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link
        rel="stylesheet"href="{{ asset('/lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css') }}">
    <link rel="stylesheet"href=" {{ asset('/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet"href=" {{ asset('/lte/plugins/icheck-bootstrap/icheck-bootstrap.css') }}">

    <link rel="stylesheet"href="{{ asset('/lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet"href="{{ asset('/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet"href="{{ asset('/dist/css/adminlte.css') }}">
    <link rel="stylesheet"href="{{ asset('/dist/css/app.css') }}"><!-- overlayScrollbars -->

    <link rel="stylesheet"href="{{ asset('/lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!--     Daterange picker -->
    <link rel="stylesheet"href="{{ asset('/lte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet"href="{{ asset('/lte/plugins/summernote/summernote-bs4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/dist/css/plugins/dataTables.dataTables.min.css') }}">

</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed  layout-footer-fixed @yield('body-class')">

    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                    width="60">
            </div> --}}

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light" style="background-color: #e0a800;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                {{-- logout navbar --}}
                <li class="nav-item"
                    style="display: flex; justify-content: center; align-items: center; height: 100%;">
                    <form action="{{ route('logout') }}" method="POST"
                        style="display: flex; justify-content: center; align-items: center; border: none; background: none;">
                        @csrf
                        <button type="submit" class="nav-link"
                            style="display: flex; justify-content: center; align-items: center; border: none; background: none;">
                            <p class="text-light btn btn-danger" style="margin: 0;">
                                Logout
                            </p>
                        </button>
                    </form>
                </li>




            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #4b0082;">

            <div class="text-center">
                <div class="logo-api rounded img-fluid"
                    style="display: flex; align-items: center; justify-content: center; margin: 20px auto; font-size: 24px; font-weight: bold; text-align: center; color: white;">
                    <img src="{{ asset('isti.jpg') }}" alt="Logo"
                        style="width: 40px; height: auto; margin-right: 10px;">
                    <span>Perpus</span>
                </div>
            </div>





            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Dashboard Link -->
                        <li class="nav-item menu-open"
                            style="margin-bottom: 2px; margin-top: 2px; background-color: #4b0082;">
                            <a href="{{ route('dashboard.index') }}"
                                class="nav-link {{ Request::routeIs('dashboard.index') ? 'active' : '' }} {{ !in_array($roleId, [1, 2]) ? 'disabled' : '' }} {{ $roleId == 10 ? 'onclick=event.preventDefault()' : '' }}"
                                style="color: white;">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item menu-open"
                            style="margin-bottom: 2px; margin-top: 2px; background-color: #4b0082;">
                            <a href="{{ route('katalog.index') }}"
                                class="nav-link {{ Request::routeIs('katalog.index') ? 'active' : '' }} {{ !in_array($roleId, [1, 2]) ? 'disabled' : '' }} {{ $roleId == 10 ? 'onclick=event.preventDefault()' : '' }}"
                                style="color: white;">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Katalog Perpustakaan</p>
                            </a>
                        </li>
                        <li class="nav-item menu-open {{ $roleId == 1 ? 'd-none' : '' }}"
                            style="margin-bottom: 2px; margin-top: 2px; background-color: #4b0082;">
                            <a href="{{ route('readlist.index') }}"
                                class="nav-link {{ Request::routeIs('readlist.index') ? 'active' : '' }} {{ !in_array($roleId, [1, 2]) ? 'disabled' : '' }} {{ $roleId == 10 ? 'onclick=event.preventDefault()' : '' }}"
                                style="color: white;">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Daftar Readlist</p>
                            </a>
                        </li>
                        <li class="nav-item menu-open"
                            style="margin-bottom: 2px; margin-top: 2px; background-color: #4b0082;">
                            <a href="{{ route('daftarpinjam.index') }}"
                                class="nav-link {{ Request::routeIs('daftarpinjam.index') ? 'active' : '' }} {{ !in_array($roleId, [1, 2]) ? 'disabled' : '' }} {{ $roleId == 10 ? 'onclick=event.preventDefault()' : '' }}"
                                style="color: white;">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Daftar Pinjam</p>
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->
        <footer class="main-footer">

        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-  ">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('/lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('/lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('/lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('/lte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('/lte/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('/lte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('/lte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('/lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('/lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('/lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('/lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('/lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('/lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('/dist/js/pages/dashboard.js') }}"></script>

    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('/dist/js/pages/dashboard3.js') }}"></script>
    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>

</body>

</html>

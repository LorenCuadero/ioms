<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ !empty($header_title) ? $header_title : '' }} IOMS</title>
    <!-- Page Icon -->
    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/rbH9RXt/pn-logo-circle.png">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/aside.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/staff.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/student.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <script defer src="{{ asset('assets/js/compile.js') }}"></script>
    <script defer src="{{ asset('assets/js/components/admin/admin.js') }}"></script>
    <script defer src="{{ asset('assets/js/components/staff/cmpt-staff-table-header.js') }}"></script>
    <script defer src="{{ asset('assets/js/components/student/cmpt-student-reports.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/toastr/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/chart.js/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Handle click on pushmenu button
            $('.navbar-nav a[data-widget="pushmenu"]').on('click', function () {
                // Toggle the collapse class on the body
                $('body').toggleClass('sidebar-collapse');

                // Toggle 'sidebar-mini' class based on window width
                if ($(window).width() < 768) {
                    $('body').toggleClass('sidebar-open');
                    $('body').addClass('mobile-view');
                    $('body').removeClass('sidebar-mini');
                }else {
                    $('body').removeClass('mobile-view');
                    $('body').removeClass('sidebar-open');
                }
            });

            $(document).on('click', function (e) {
                if (
                    !$(e.target).closest('.main-sidebar').length && // Check if the click is not within the sidebar
                    !$(e.target).closest('.navbar-nav').length && // Check if the click is not within the navbar
                    $('body').hasClass('sidebar-open') // Check if the sidebar is open
                ) {
                    // Close the sidebar
                    $('body').removeClass('sidebar-open');
                }
            });

        });


        $(document).ready(function () {
            // Add an "active" class to the clicked navigation item
            $('.nav-link').on('click', function () {
                // Remove active class from all other navigation items
                $('.nav-link').removeClass('active_tab');

                // Add active class to the clicked navigation item
                $(this).addClass('active_tab');
            });
        });


    </script>
</head>
<style>
    .flex-container {
        display: flex;
    }

    .right-column {
        flex: 1;
        display: flex;
        flex-direction: column;
        margin-right: 10px; /* Adjust as needed */
        height: 200px; /* Set a fixed height */
    }

    .flex-container.align-middle {
        align-items: center;
    }

    .scrollable-content {
        overflow: auto;
    }
    @media (max-width: 767px) {
        .table-responsive {
            overflow-x: auto;
        }
    }

    .nav-pills .nav-item .nav-link.active {
        background-color: #1f3c88;
        color: white;
    }

    /* For WebKit browsers (Chrome, Safari) */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* For Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

</style>

<body class="sidebar-mini layout-fixed scrollable-content" style="height: auto;">

{{-- <body class="hold-transition sidebar-mini layout-fixed" data-page="{{ Route::currentRouteName() }}"> --}}
    <div class="wrapper">
        @include('layouts.student.loading')
        @include('layouts.student.header')
        @include('layouts.student.aside')
        @include('modals.mdl-logout-confirmation')
        @include('modals.mdl-change-pass-confirmation')
        @include('assets.asst-loading-spinner')
        <div class="content-wrapper text-center p-3">
                <span>
                    @if (session('incorrect-password'))
                        <script>
                            toastr.error("{{ session('incorrect-password') }}");
                        </script>
                    @endif

                    @if (session('email-not-found'))
                        <script>
                            toastr.error("{{ session('email-not-found') }}");
                        </script>
                    @endif

                    @if (session('success'))
                        <script>
                            toastr.success("{{ session('success') }}");
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            toastr.error("{{ session('error') }}");
                        </script> @endif
                </span>
            @yield('content')
        </div>
        @include('layouts.student.footer')
    </div>
    @stack('js')
</body>

</html>

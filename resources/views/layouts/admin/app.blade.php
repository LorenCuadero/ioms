<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>{{ config('app.name', 'IOMS') }}</title> --}}
    <title>{{ !empty($header_title) ? $header_title : '' }} IOMS</title>
    <!-- Page Icon -->
    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/rbH9RXt/pn-logo-circle.png">

    <!-- Stylesheets -->
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

    <!-- Inline Styles -->
    <style>
        .custom-modal-width-on-modal {
            max-width: 1000px;
            width: 100%;
        }
        body {
            font-family: 'Poppins', sans-serif !important;
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

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

        <!-- Scripts -->
    <script defer src="{{ asset('assets/js/compile.js') }}"></script>
    <script defer src="{{ asset('assets/js/components/admin/admin.js') }}"></script>
    <script defer src="{{ asset('assets/js/components/staff/cmpt-staff-table-header.js') }}"></script>
    <script defer src="{{ asset('assets/js/components/student/cmpt-student-reports.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/toastr/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte/plugins/chart.js/Chart.min.js"></script>

</head>

<body class="sidebar-mini layout-fixed scrollable-content" data-page="{{ Route::currentRouteName() }}" style="height: auto;">
    <div class="wrapper">
        @include('layouts.admin.loading')
        @include('layouts.admin.header')
        @include('layouts.admin.aside')
        @include('modals.mdl-logout-confirmation')
        @include('assets.asst-loading-spinner')
        @include('modals.mdl-change-pass-confirmation')

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
        @include('layouts.admin.footer')
    </div>


    <script>
        $(document).ready(function() {
            // Handle click on pushmenu button
            $('.navbar-nav a[data-widget="pushmenu"]').on('click', function() {
                // Toggle the collapse class on the body
                $('body').toggleClass('sidebar-collapse');

                // Toggle 'sidebar-mini' class based on window width
                if ($(window).width() < 992 && $(window).width() > 768) {

                    $('body').removeClass('sidebar-collapse');
                    $('body').addClass('sidebar-open');
                }
                if ($(window).width() < 768) {
                    $('body').toggleClass('sidebar-open');
                    $('body').addClass('mobile-view');
                    $('body').removeClass('sidebar-mini');
                } else {
                    $('body').removeClass('mobile-view');
                }
            });

            if ($(window).width() < 992 && $(window).width() > 768) {
                // console.log('testing');
                $('body').addClass('sidebar-collapse');
                $(document).on('click', function(e) {
                    if (
                        !$(e.target).closest('.main-sidebar').length &&
                        // Check if the click is not within the sidebar
                        !$(e.target).closest('.navbar-nav').length &&
                        // Check if the click is not within the navbar
                        $('body').hasClass('sidebar-open') // Check if the sidebar is open
                    ) {
                        console.log('testing');
                        $('body').removeClass('sidebar-open');
                        $('body').toggleClass('sidebar-collapse');
                    }
                });
            }

            $(document).on('click', function(e) {
                if (
                    !$(e.target).closest('.main-sidebar').length &&
                    // Check if the click is not within the sidebar
                    !$(e.target).closest('.navbar-nav').length &&
                    // Check if the click is not within the navbar
                    $('body').hasClass('sidebar-open') // Check if the sidebar is open
                ) {
                    // Close the sidebar
                    $('body').removeClass('sidebar-open');
                }
            });

        });

        $(document).ready(function() {
            // Add an "active" class to the clicked navigation item
            $('.nav-link').on('click', function() {
                // Remove active class from all other navigation items
                $('.nav-link').removeClass('active');

                // Add active class to the clicked navigation item
                $(this).addClass('active');
            });
        });

        $(document).ready(function() {
            // Capture the click event on table rows with class "table-row1"
            $(".table-row1").click(function() {
                // Get the data attributes from the clicked row
                var studentId = $(this).find("td:first")
                    .text(); // Assuming the first column contains the student ID
                var route = "{{ route('admin.studentPageCounterpartRecords', ['id' => ':studentId']) }}";

                // Replace ':studentId' in the route with the actual student ID
                route = route.replace(':studentId', studentId);

                // Redirect to the desired route
                window.location.href = route;
            });

            // Handle the click event for the "Add Student" button
            $("#selectToAddStudentCounterpart").click(function() {
                const addModal = $("#student-selection-counterpart-modal");
                addModal.modal('show');
            });
        });

        $(document).ready(function() {
            // Capture the click event on table rows with class "table-row1"
            $(".table-rowGraduation").click(function() {
                // Get the data attributes from the clicked row
                var studentId = $(this).find("td:first")
                    .text(); // Assuming the first column contains the student ID
                var route = "{{ route('admin.studentGraduationFeeRecords', ['id' => ':studentId']) }}";

                // Replace ':studentId' in the route with the actual student ID
                route = route.replace(':studentId', studentId);

                // Redirect to the desired route
                window.location.href = route;
            });
        });

        $(document).ready(function() {
            // Capture the click event on table rows with class "table-row1"
            $(".table-rowMedical").click(function() {
                // Get the data attributes from the clicked row
                var studentId = $(this).find("td:first")
                    .text(); // Assuming the first column contains the student ID
                var route = "{{ route('admin.studentMedicalShareRecords', ['id' => ':studentId']) }}";

                // Replace ':studentId' in the route with the actual student ID
                route = route.replace(':studentId', studentId);

                // Redirect to the desired route
                window.location.href = route;
            });
        });

        $(document).ready(function() {
            $("#selectToAddStudentMedicalShare").click(function() {
                const addModal = $("#student-selection-medical-share-modal");

                addModal.modal("show");
            });
        });

        $(document).ready(function() {
            // Capture the click event on table rows with class "table-row1"
            $(".table-rowPersonal").click(function() {
                // Get the data attributes from the clicked row
                var studentId = $(this).find("td:first")
                    .text(); // Assuming the first column contains the student ID
                var route =
                    "{{ route('admin.reports.studentPersonalCARecords', ['id' => ':studentId']) }}";

                // Replace ':studentId' in the route with the actual student ID
                route = route.replace(':studentId', studentId);

                // Redirect to the desired route
                window.location.href = route;
            });
        });

        $(document).ready(function() {
            // Add an "active" class to the clicked navigation item
            $('.nav-link').on('click', function() {
                // Remove active class from all other navigation items
                $('.nav-link').removeClass('active');

                // Add active class to the clicked navigation item
                $(this).addClass('active');
            });
        });

        $(document).ready(function() {
            $('.printButtonOnAdminAcademicReports').on('click', function() {
                var studentId = $(this).data('student-id');
                // Show the corresponding print pane
                $('#grades-' + studentId).show();
            });
        });

        $(document).ready(function() {
            var currentYear = new Date().getFullYear();
            for (var i = currentYear; i >= currentYear - 5; i--) {
                $(".yearDropdown").append(
                    $("<option>", {
                        value: i,
                        text: i,
                    })
                );
            }

            // SOA Email Form Validation
            const selectedMonth = $("#monthDropdown option:selected").val();
            const selectedYear = $(".yearDropdown option:selected").val();

            $("#month_on_soa").val(selectedMonth);
            $("#year_on_soa").val(selectedYear);
        });
    </script>
</body>

</html>

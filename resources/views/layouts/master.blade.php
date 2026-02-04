<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('adm/assets/images/favicon.png') }}">
    <title>@yield('title') | Pangkalan LPG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('adm/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adm/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adm/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('adm/assets/libs/select2/dist/css/select2.min.css') }}">
    <link href="{{ asset('adm/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('adm/dist/css/style.min.css') }}" rel="stylesheet">

    <style>
        /* Custom Repeater Styles */
        .item-row {
            border: 1px solid #e9ecef !important;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .item-row:hover {
            border-color: #007bff !important;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.1);
        }
        
        .remove-item {
            transition: all 0.2s ease;
        }
        
        .remove-item:hover {
            transform: scale(1.05);
        }
        
        #add-item {
            transition: all 0.2s ease;
        }
        
        #add-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }
        
        #item-counter {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        /* Gradient backgrounds */
        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8, #138496) !important;
        }
        
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
        }
        
        .bg-gradient-dark {
            background: linear-gradient(135deg, #343a40, #23272b) !important;
        }
        
        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800) !important;
        }
        
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #6c757d, #545b62) !important;
        }
        
        /* Card hover effects */
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Debug styles for user info */
        .debug-user-info {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 9999;
            display: none;
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <div id="main-wrapper">
        @include('layouts.header')
        @include('layouts.aside')
        @yield('content')
    </div>

    <!-- Scripts -->
    <!-- jQuery - Load only once -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Template Scripts -->
    <script src="{{ asset('adm/dist/js/app.min.js') }}"></script>
    <script src="{{ asset('adm/dist/js/app.init.js') }}"></script>
    <script src="{{ asset('adm/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('adm/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('adm/assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('adm/dist/js/waves.js') }}"></script>
    <script src="{{ asset('adm/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('adm/dist/js/custom.js') }}"></script>

    <!-- Chart Scripts -->
    <script src="{{ asset('adm/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('adm/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('adm/assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('adm/assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('adm/dist/js/pages/dashboards/dashboard3.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('adm/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('adm/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="{{ asset('adm/dist/js/pages/datatable/datatable-advanced.init.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ REMOVED: Global Select2 initialization to prevent conflicts -->
    <script>
        // Logout functionality
        $(document).ready(function() {
            $('#btnLogout').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin logout?',
                    text: "Kamu akan keluar dari sistem.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#logoutForm').submit();
                    }
                });
            });

            // Checkbox functionality
            const rusakYa = $('#rusakYa');
            const rusakTidak = $('#rusakTidak');

            rusakYa.change(function() {
                if (this.checked) {
                    rusakTidak.prop('checked', false);
                }
            });

            rusakTidak.change(function() {
                if (this.checked) {
                    rusakYa.prop('checked', false);
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success') . ' ' . Auth::user()->nama_lengkap),
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>

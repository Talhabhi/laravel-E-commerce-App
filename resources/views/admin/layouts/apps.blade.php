<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Administrative Panel</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-16x16.png') }}">

    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />



    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <!-- summernote  -->
<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">

    <!-- Dropzone CSS -->


    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Right navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <div class="navbar-nav pl-2">
                <!-- <ol class="breadcrumb p-0 m-0 bg-white">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol> -->
            </div>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
                        <img src="{{ asset('admin-assets/img/avatar5.png') }}" class='img-circle elevation-2'
                            width="40" height="40" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
                        @if (Auth::guard('admin')->check())
                            <h4 class="h4 mb-0"><strong>{{ Auth::guard('admin')->user()->name }}</strong></h4>
                            <div class="mb-3">{{ Auth::guard('admin')->user()->email }}</div>
                        @else
                            <h4 class="h4 mb-0"><strong>Guest</strong></h4>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-lock mr-2"></i> Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.logout') }}" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        @include('admin.layouts.sidebar')
        <!-- Main Sidebar Container -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('content');
            @yield('customJs');


            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} AmazingShop All rights reserved.</strong>
        </footer>
    </div>


    <!-- latest bootsstrap script  -->
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>








    <!-- AdminLTE App -->
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
    <!-- summernote script   -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('admin-assets/js/demo.js') }}"></script>

     <script>
        $(document).ready(function() {
            // Initialize Summernote on the #summernote textarea
            $('#summernote').summernote({
                height: 250, // Adjust editor height
                // placeholder: 'Write your content here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],

            });
            console.log('summernote is intialized');

        });
    </script>
    @yield('customJS');

<script>
    // Slug generation based on name input
    $("#name").change(function() {
        $("button[type=submit]").prop('disabled', true);
        let element = $(this);

        $.ajax({
            url: "{{ route('getSlug') }}",
            type: 'GET',
            data: {
                title: element.val()
            },
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status === true) {
                    $("#slug").val(response.slug);
                }
            }
        });
    });
</script>








</body>

</html>

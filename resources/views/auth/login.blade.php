<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API LOGIN</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('\lte\plugins\fontawesome-free\css\all.css') }}">

    <link rel="stylesheet"href=" {{ asset('\lte\plugins\icheck-bootstrap\icheck-bootstrap.min.css') }}">
    <link rel="stylesheet"href=" {{ asset('dist\css\adminlte.min.css') }}">

</head>

<body class="hold-transition login-page">
    <div class="login-box">


        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/login" class="h1"><b>LOGIN</b></a>
            </div>
            <div class="card-body">
                <img src="dist/img/LogoApi.png" alt="Api Logo" class="logo-api rounded img-fluid w-50"
                    style="display: block; margin: 0 auto;"></img>

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="input-group mb-3">
                        <input type="username" name="username" class="form-control" placeholder="Username"
                            value="{{ Session::get('username') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="bi bi-person-fill"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>




            </div>

        </div>

    </div>


    <link rel="stylesheet"href=" {{ asset('dist\css\adminlte.min.css') }}">

    <script src="{{ asset('lte\plugins\jquery\jquery.min.js') }}"></script>
    <script src="{{ asset('lte\plugins\bootstrap\js\bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('dist\js/adminlte.min.js?v=3.2.0') }}"></script>

    <script>
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengunggah',
                html: `
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        `, // Tampilkan semua pesan error dalam bentuk list
            });
        @endif
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PERPUS REGIST</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet"href=" {{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css?v=3.2.0">

</head>

<body class="hold-transition login-page">
    <div class="login-box">


        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/login" class="h1"><b>REGISTER</b></a>
            </div>
            <div class="card-body">
                <form action="{{ route('register') }}" method="post">
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
                        <input type="text" name="nama" class="form-control" placeholder="Nama"
                            value="{{ old('nama') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="bi bi-person-fill"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="bi bi-person-fill"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>
                <a href="/login" style="display: block; text-align: center; font-size: 15px;">Sudah Punya Akun?</a>

            </div>

        </div>

    </div>

    <script src="lte/plugins/jquery/jquery.min.js"></script>
    <script src="lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script>
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

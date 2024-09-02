@extends('layout/main')
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
@section('content')
    <div class="content-wrapper">
        <div class="p-3"> <!-- Menambahkan padding untuk menggeser isi ke dalam -->
            <p class="pl-3 pb-3 font-weight-bold h3">Data Absensi Karyawan</p>

            <!-- Tombol Tambah Check-in dan Check-out -->


            <div class="p-3 ml-3 text-black card"> <!-- Menambahkan padding dan margin -->
                <table id="myTable" class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPK</th>
                            <th>Tanggal</th>
                            <th>Waktu Check in</th>
                            <th>Waktu Login dashboard</th>

                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>


    @include('performa.script')
@endsection

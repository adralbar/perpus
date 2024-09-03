@extends('layout/main')
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Data Absensi Karyawan</p>
            <div class="p-3 ml-3 text-black card">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                        data-bs-target="#logModal">
                        Tambah Log
                    </button>
                    <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                        data-bs-target="#userIdModal">
                        Tambah User Id
                    </button>

                </div>
                <table id="myTable" class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPK</th>
                            <th>Tanggal</th>
                            <th>Waktu Check in</th>
                            <th>Waktu Login Dashboard</th>
                            <th>Selisih Waktu</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkinModalLabel">Tambah Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="logForm">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">User_id</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" required>
                        </div>
                        <div class="form-group">
                            <label for="datetime">Created_at</label>
                            <input type="datetime-local" class="form-control" id="created_at" name="created_at" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="userIdModal" tabindex="-1" aria-labelledby="userIdModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userIdModalLabel">Tambah User Id</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="userIdForm">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Id</label>
                            <input type="text" class="form-control" id="id" name="id" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="text">NPK</label>
                            <input type="text" class="form-control" id="npk" name="npk" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @include('performa.script')
@endsection

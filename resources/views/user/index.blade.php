@extends('layout/main')

<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">

@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Data Karyawan</p>
                <div class="p-3 ml-3 text-black card">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal"
                                data-target="#modal-tambahUser">
                                Tambah Karyawan
                            </button>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table id="myTable" class="table table-light table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPK</th>
                                    <th>Nama</th>
                                    <th>No. Telp</th>
                                    <th>Division</th>
                                    <th>Departemen</th>
                                    <th>Section</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userData as $dt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dt->npk }}</td>
                                        <td>{{ $dt->nama }}</td>
                                        <td>{{ $dt->no_telp }}</td>
                                        <td>{{ $dt->division->nama }}</td>
                                        <td>{{ $dt->department->nama }}</td>
                                        <td>{{ $dt->section->nama }}</td>
                                        <td>{{ $dt->role_id }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('/user/detail/' . $dt->npk) }}" class="btn btn-warning"><i
                                                        class="fas fa-eye"></i></a>
                                                <a href="{{ url('/user/edit/' . $dt->npk) }}" class="btn btn-success"><i
                                                        class="fas fa-edit"></i></a>
                                                <form action="{{ url('/user/delete/' . $dt->npk) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data {{ $dt->nama }}?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data karyawan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah User -->
            <div class="modal fade" id="modal-tambahUser" tabindex="-1" aria-labelledby="tambahUserLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahUserLabel">Tambah Karyawan</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <form action="{{ url('user') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="npk">NPK</label>
                                                <input type="text" class="form-control" name="npk" id="npk"
                                                    placeholder="NPK">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" name="password" id="password"
                                                    placeholder="Password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Karyawan</label>
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Nama Karyawan">
                                    </div>
                                    <div class="form-group">
                                        <label>No. Telepon</label>
                                        <input type="number" class="form-control" name="no_telp" id="no_telp"
                                            placeholder="Nomor Telepon">
                                    </div>
                                    <div class="form-group">
                                        <label>Division</label>
                                        <select class="form-control" name="division_id" id="division_id">
                                            @foreach ($division as $div)
                                                <option value="{{ $div->id }}">{{ $div->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Departemen</label>
                                        <select class="form-control" name="department_id" id="department_id">
                                            <option value="">Pilih Departemen</option>
                                            @foreach ($department as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Section</label>
                                        <select class="form-control" name="section_id" id="section_id">
                                            <option value="">Pilih Section</option>
                                            <!-- Sections will be populated based on department selection -->
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col mt-3">
                                            <button type="button" class="btn btn-default"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });


        $(document).ready(function() {
            // Filter departments based on division selection
            $('#division_id').change(function() {
                var divisionId = $(this).val();
                $.ajax({
                    url: '/departments/' + divisionId,
                    type: 'GET',
                    success: function(data) {
                        $('#department_id').empty();
                        $('#department_id').append(
                            '<option value="">Pilih Departemen</option>');
                        $.each(data, function(index, department) {
                            $('#department_id').append('<option value="' + department
                                .id + '">' + department.nama + '</option>');
                        });
                        $('#section_id').empty().append(
                            '<option value="">Pilih Section</option>'); // Clear sections
                    }
                });
            });

            // Filter sections based on department selection
            $('#department_id').change(function() {
                var departmentId = $(this).val();
                $.ajax({
                    url: '/sections/' + departmentId,
                    type: 'GET',
                    success: function(data) {
                        $('#section_id').empty();
                        $('#section_id').append('<option value="">Pilih Section</option>');
                        $.each(data, function(index, section) {
                            $('#section_id').append('<option value="' + section.id +
                                '">' + section.nama + '</option>');
                        });
                    }
                });
            });
        });

        @if (session('error'))
            <
            div class = "alert alert-danger" >
            {{ session('error') }}
                <
                /div>
        @endif
    </script>
@endsection

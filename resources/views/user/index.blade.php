@extends('layout/main')
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">

@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Data Karyawan</p>
                <div class="p-3 ml-3 text-black card">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        {{-- SweetAlert untuk berhasil --}}
                        @if (session('success'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: '{{ session('success') }}',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });
                                });
                            </script>
                        @endif

                        {{-- SweetAlert untuk error --}}
                        @if ($errors->any())
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: '{{ session('danger') }}',
                                        icon: 'danger',
                                        confirmButtonText: 'OK'
                                    });
                                });
                            </script>
                        @endif
                        <button type="button" class="btn btn-primary btn-sm float-right ml-2 d-none d-sm-block"
                            data-toggle="modal" data-target="#modal-tambahUser">
                            <i class="fas fa-plus"></i> Tambah Karyawan
                        </button>
                        <a href="{{ route('exportUsers') }}"
                            class="btn btn-success btn-sm float-right ml-2 d-none d-sm-block">
                            Ekspor Data Pengguna
                        </a>

                    </div>


                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="myTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NPK Sistem</th>
                                        <th>NPK</th>
                                        <th>Nama</th>
                                        <th>No. Telp</th>
                                        <th>Division</th>
                                        <th>Departemen</th>
                                        <th>Section</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($userData as $dt)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $dt->npk_sistem }}</td>
                                            <td>{{ $dt->npk }}</td>
                                            <td>{{ $dt->nama }}</td>
                                            <td>{{ $dt->no_telp }}</td>
                                            <td>{{ $dt->division->nama }}</td>
                                            <td>{{ $dt->department->nama }}</td>
                                            <td>{{ $dt->section->nama }}</td>
                                            <td>{{ $dt->role->nama }}</td>
                                            <td>
                                                <!-- Menampilkan status aktif/non-aktif -->
                                                @if ($dt->status == 1)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Non Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Button View -->
                                                <a href="{{ url('/user/detail/' . $dt->npk) }}"
                                                    class="btn btn-warning btn-sm"><i class="fas fa-eye"></i></a>
                                                <a href="{{ url('/user/edit/' . $dt->npk) }}"
                                                    class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                                <form action="{{ url('/user/delete/' . $dt->npk) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data {{ $dt->nama }}?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data karyawan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div class="card-footer"></div>

                    {{-- Modal Tambah User --}}
                    <div class="modal fade" id="modal-tambahUser">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Tambah User</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>


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
                                                    <label for="npk_sistem">NPK Sistem</label>
                                                    <input type="npk_sistem" class="form-control" name="npk_sistem"
                                                        id="npk_sistem" placeholder="npk_sistem">
                                                    <input type="hidden" name="status" id="status" value="1">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" name="password"
                                                        id="password" placeholder="Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Karyawan</label>
                                                    <input type="text" class="form-control" name="nama" id="nama"
                                                        placeholder="Nama Karyawan">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No. Telepon</label>
                                                    <input type="text" inputmode="numeric" class="form-control"
                                                        name="no_telp" id="no_telp" placeholder="Nomor Telepon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="form-control select2bs4" name="role_id" style="width: 100%;">
                                                @foreach ($role as $rl)
                                                    <option value="{{ $rl->id }}">{{ $rl->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Section</label>
                                            <select class="form-control select2bs4" name="section_id" id="section_id"
                                                style="width: 100%;">
                                                <option value="">Pilih Section</option>
                                                @foreach ($section as $sec)
                                                    <option value="{{ $sec->id }}">{{ $sec->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Departemen</label>
                                            <select class="form-control select2bs4" name="department_id"
                                                id="department_id" style="width: 100%;" readonly>
                                                <!-- Departemen akan terisi otomatis -->
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Division</label>
                                            <select class="form-control select2bs4" name="division_id" id="division_id"
                                                style="width: 100%;" readonly>
                                                <!-- Division akan terisi otomatis -->
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col mt-3">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" name="submit"
                                                    class="btn btn-primary float-right">Simpan</button>
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
    </div>
    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script> --}}
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script> <!-- Dual Listbox -->
    </script>

    <script src="{{ asset('dist/js/xlsx.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true, // Optional: For responsive tables
                searching: true, // Enables search
                ordering: true, // Enables ordering
                autoWidth: false, // Adjust table width
            });

            $('#section_id').on('change', function() {
                var sectionId = $(this).val();

                if (sectionId) {
                    $.ajax({
                        url: "{{ route('section.data') }}",
                        type: "GET",
                        data: {
                            section_id: sectionId
                        },
                        success: function(response) {
                            $('#department_id').html('<option value="' + response.department
                                .id + '">' + response.department.nama + '</option>');
                            $('#division_id').html('<option value="' + response.division.id +
                                '">' + response.division.nama + '</option>');
                        },
                        error: function(xhr) {
                            console.log("Terjadi kesalahan: " + xhr.responseText);
                        }
                    });
                } else {
                    $('#department_id').html('<option value="">Pilih Departemen</option>');
                    $('#division_id').html('<option value="">Pilih Division</option>');
                }
            });
        });
    </script>
@endsection

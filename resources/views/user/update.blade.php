@extends('layout.main')


@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-6">
                    <div class="p-3">
                        <p class="pl-3 pb-3 font-weight-bold h3">Update Data Karyawan</p>
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('user.update', $user->npk) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="npk">NPK</label>
                                                <input type="text" class="form-control" name="npk" id="npk"
                                                    value="{{ old('npk', $user->npk) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="npk_sistem">NPK Sistem</label>
                                                <input type="text" class="form-control" name="npk_sistem" id="npk_sistem"
                                                    value="{{ old('npk_sistem', $user->npk_sistem) }}"
                                                    placeholder="npk_sistem">
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
                                            value="{{ old('nama', $user->nama) }}" placeholder="Nama Karyawan">
                                    </div>
                                    <div class="form-group">
                                        <label>No Telepon</label>
                                        <input type="text" class="form-control" name="no_telp" id="no_telp"
                                            value="{{ old('no_telp', $user->no_telp) }}" placeholder="no_telp">
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select class="form-control select2bs4" name="role_id" style="width: 100%;">
                                            @foreach ($role as $rl)
                                                <option value="{{ $rl->id }}"
                                                    {{ $rl->id == $user->role_id ? 'selected' : '' }}>
                                                    {{ $rl->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Section</label>
                                        <select class="form-control select2bs4" name="section_id" id="section_id"
                                            style="width: 100%;">
                                            <option value="">Pilih Section</option>
                                            @foreach ($section as $sec)
                                                <option value="{{ $sec->id }}"
                                                    @if (old('section_id', $user->section_id) == $sec->id) selected @endif>
                                                    {{ $sec->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Departemen</label>
                                        <select class="form-control select2bs4" name="department_id" id="department_id"
                                            style="width: 100%;" readonly>
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
                                            <a href="{{ route('karyawan.index') }}" class="btn btn-danger">Batal</a>
                                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <div class="p-3 mt-2">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Status Karyawan</h3>
                            </div>
                            <div class="card-body">
                                <input type="checkbox" name="status" id="statusCheckbox" data-bootstrap-switch>
                                <input type="hidden" name="status" id="status" value="{{ $user->status ?? 1 }}">
                                <!-- Nilai default 1 jika tidak ada -->
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

        <script>
            $(document).ready(function() {
                // Mengatur nilai awal berdasarkan data yang sudah ada
                const sectionId = $('#section_id').val();
                updateDepartmentAndDivision(sectionId);

                // Ketika section dipilih
                $('#section_id').on('change', function() {
                    var sectionId = $(this).val();
                    updateDepartmentAndDivision(sectionId);
                });

                // Fungsi untuk memperbarui Departemen dan Division berdasarkan section yang dipilih
                function updateDepartmentAndDivision(sectionId) {
                    if (sectionId) {
                        $.ajax({
                            url: "{{ route('section.data') }}",
                            type: "GET",
                            data: {
                                section_id: sectionId
                            },
                            success: function(response) {
                                // Update Departemen
                                $('#department_id').html('<option value="' + response.department.id + '">' +
                                    response.department.nama + '</option>');
                                // Update Division
                                $('#division_id').html('<option value="' + response.division.id + '">' +
                                    response.division.nama + '</option>');
                            },
                            error: function(xhr) {
                                console.log("Terjadi kesalahan: " + xhr.responseText);
                            }
                        });
                    } else {
                        // Reset dropdown jika tidak ada section yang dipilih
                        $('#department_id').html('<option value="">Pilih Departemen</option>');
                        $('#division_id').html('<option value="">Pilih Division</option>');
                    }
                }
            });
            document.addEventListener("DOMContentLoaded", function() {
                // Ambil nilai awal dari hidden input status dan setel posisi checkbox
                const initialStatus = document.getElementById("status").value === "1";
                $('#statusCheckbox').prop('checked', initialStatus).bootstrapSwitch(); // Set posisi sesuai nilai awal

                // Event listener untuk memperbarui nilai saat checkbox berubah
                $('#statusCheckbox').on('switchChange.bootstrapSwitch', function(event, state) {
                    updateStatus();
                });

                // Fungsi untuk memperbarui nilai hidden input berdasarkan status checkbox
                function updateStatus() {
                    const status = document.getElementById("statusCheckbox").checked ? 1 : 0;
                    document.getElementById("status").value = status;
                }
            });
        </script>
    @endsection

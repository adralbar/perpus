@extends('layout/main')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">

@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Data Absensi Karyawan</p>
            <div class="p-3 ml-3 text-black card">
                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                        data-bs-target="#shiftModal" onclick="resetForm()">
                        Tambah Shift Karyawan
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#uploadModal">
                        Upload File
                    </button>
                </div>
                <table id="myTable" class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPK</th>
                            <th>Divisi</th>
                            <th>Departement</th>
                            <th>Section</th>
                            <th>Shift 1</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah/Edit Karyawan -->
    <div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftLabel">Tambah/Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="shiftForm" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="shiftId" name="id">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="npk">NPK</label>
                            <input type="text" class="form-control" id="npk" name="npk" required>
                        </div>
                        <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <input type="text" class="form-control" id="divisi" name="divisi" required>
                        </div>
                        <div class="form-group">
                            <label for="departement">departement</label>
                            <input type="text" class="form-control" id="departement" name="departement" required>
                        </div>
                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" class="form-control" id="section" name="section" required>
                        </div>
                        <div class="form-group">
                            <label for="shift1">Shift 1</label>
                            <input type="text" class="form-control" id="shift1" name="shift1" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <input type="text" class="form-control" id="status" name="status" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Upload File -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadLabel">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" method="POST" enctype="multipart/form-data"
                        action="{{ route('shift.import') }}">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('shift.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'npk',
                        name: 'npk'
                    },
                    {
                        data: 'divisi',
                        name: 'divisi'
                    },
                    {
                        data: 'departement',
                        name: 'departement'
                    },
                    {
                        data: 'section',
                        name: 'section'
                    },
                    {
                        data: 'shift1',
                        name: 'shift1'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                    w
                    `;
                        }
                    }
                ]
            });

            $('#shiftForm').submit(function(e) {
                e.preventDefault();
                var url = '{{ route('shift.store') }}';
                var method = 'POST';
                var formData = $(this).serialize();

                if ($('#shiftId').val()) {
                    url = '{{ route('shift.update', ':id') }}'.replace(':id', $('#shiftId').val());
                    method = 'PUT';
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response) {
                        $('#shiftModal').modal('hide');
                        table.ajax.reload();
                        alert(response.success);
                    },
                    error: function(response) {
                        alert('Terjadi kesalahan');
                    }
                });
            });
        });

        function editShift(id) {
            $.ajax({
                url: '{{ route('shift.edit', ':id') }}'.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    $('#shiftId').val(response.id);
                    $('#nama').val(response.nama);
                    $('#npk').val(response.npk);
                    $('#divisi').val(response.divisi);
                    $('#departement').val(response.section);
                    $('#section').val(response.section);
                    $('#shift1').val(response.shift1);
                    $('#status').val(response.status);
                    $('#shiftLabel').text('Edit Karyawan');
                    $('#saveButton').text('Update');
                    $('#shiftModal').modal('show');
                }
            });
        }

        function deleteShift(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '{{ route('shift.destroy', ':id') }}'.replace(':id', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#myTable').DataTable().ajax.reload();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.error);
                    }
                });
            }
        }



        function resetForm() {
            $('#shiftForm')[0].reset();
            $('#shiftId').val('');
            $('#shiftLabel').text('Tambah Karyawan');
            $('#saveButton').text('Simpan');
        }
    </script>
@endsection

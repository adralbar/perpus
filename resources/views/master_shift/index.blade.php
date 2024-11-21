@extends('layout.main')
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card-header">
                        <h5>Master Shift</h5>
                        <button type="button" class="btn btn-primary mt-2" data-toggle="modal"
                            data-target="#createShiftModal">
                            <i class="fas fa-plus mr-2"></i>Tambah Shift
                        </button>
                    </div>

                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table id="tbl" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Shift Name</th>
                                        <th>Waktu</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah -->
    <div id="createShiftModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('master-shift.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Shift</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="shift_name">Shift Name</label>
                            <input type="text" class="form-control" id="shift_name" name="shift_name" required>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editShiftModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Shift</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        <div class="form-group">
                            <label for="edit_shift_name">Shift Name</label>
                            <input type="text" class="form-control" id="edit_shift_name" name="shift_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_start_time">Start Time</label>
                            <input type="time" class="form-control" id="edit_start_time" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_end_time">End Time</label>
                            <input type="time" class="form-control" id="edit_end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master-shift.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'shift_name',
                        name: 'shift_name'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            window.openEditModal = function(id) {
                $.get("{{ route('master-shift.edit', ':id') }}".replace(':id', id), function(data) {
                    $('#editForm').attr('action', "{{ route('master-shift.update', ':id') }}".replace(
                        ':id', id));
                    $('#edit_shift_name').val(data.shift_name);
                    $('#edit_start_time').val(data.start_time);
                    $('#edit_end_time').val(data.end_time);
                    $('#editShiftModal').modal('show');
                });
            };
        });

        $(document).on('click', '.btn-delete', function(event) {
            event.preventDefault();

            const form = $(this).closest('form');

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection

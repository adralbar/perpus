@extends('layout/main')


<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">

@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Data Absensi Karyawan</p>
            <div class="p-3 ml-3 text-black card">
                <div class="mb-3">
                    <!-- Button to trigger the modal -->
                    <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                        data-bs-target="#shiftModal" onclick="resetForm()">
                        Tambah Shift Karyawan
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#uploadModal">
                        Upload File
                    </button>
                </div>
                <div class=table-responsive>
                    <table id="myTable" class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NPK Api</th>
                                <th>Shift</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftLabel">Tambah/Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="shiftForm">
                        @csrf
                        <input type="hidden" id="shiftId" name="id">

                        <div class="form-group">
                            <label for="npk">NPK Api</label>
                            <input type="text" class="form-control" id="npk" name="npk" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="form-group">
                            <label for="npk">Shift</label>
                            <input type="text" class="form-control" id="shift1" name="shift1" required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Upload File -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadLabel">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm" method="POST" enctype="multipart/form-data" action="{{ route('shift.import') }}">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx"
                                required>
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
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>

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
                        data: 'shift1',
                        name: 'shift1'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'id', // Menggunakan 'id' untuk referensi penghapusan/edit data
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
    <div style="display: flex; gap: 5px;">
        <button class="btn btn-primary btn-sm" onclick="editShift(${data})">Edit</button>
        <button class="btn btn-danger btn-sm" onclick="deleteShift(${data})">Delete</button>
    </div>
    `;
                        }
                    }
                ]
            });

            $('#shiftForm').submit(function(e) {
                e.preventDefault(); // Mencegah pengiriman form secara default
                var id = $('#shiftId').val(); // Ambil nilai ID dari input
                var url, method;

                if (id === '') {
                    url = '{{ route('shift.store') }}';
                    method = 'POST';
                } else {
                    url = '{{ route('shift.update', ':id') }}'.replace(':id',
                        id);
                    method = 'PUT';
                }

                // AJAX request
                $.ajax({
                    url: url, // URL yang ditentukan
                    method: method, // Metode yang ditentukan
                    data: $(this)
                        .serialize(), // Mengambil data dari form dan mengubahnya menjadi string
                    success: function(response) {
                        $('#shiftModal').modal('hide'); // Menutup modal setelah sukses
                        table.ajax.reload(); // Memuat ulang data tabel
                        alert(response.success); // Menampilkan pesan sukses
                    },
                    error: function(xhr, status, error) {
                        // Menangani error jika request gagal
                        console.error(xhr.responseText); // Log pesan error ke konsol
                        alert('Terjadi kesalahan: ' + xhr.responseJSON
                            .message); // Tampilkan pesan kesalahan
                    }
                });

                // Untuk debugging
                console.log('URL:', url);
                console.log('Method:', method);
                console.log('data:', $(this).serialize());
            });

        });

        function editShift(id) {
            $.ajax({
                url: '{{ route('shift.edit', ':id') }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    if (response.result) {
                        $('#shiftModal').modal('show');
                        $('#shiftId').val(id); // Set the ID
                        $('#npk').val(response.result.npk);
                        $('#shift1').val(response.result.shift1);
                        $('#date').val(response.result.date);
                        $('#shiftLabel').text('Edit Karyawan');
                    } else {
                        alert('Data not found.');
                    }
                },
                error: function() {
                    alert('Error fetching data.');
                }
            });
        }

        function deleteShift(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '{{ route('shift.destroy', ':id') }}'.replace(':id', id),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#myTable').DataTable().ajax.reload();

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
@endsection

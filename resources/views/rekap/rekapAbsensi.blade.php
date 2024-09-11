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
                        data-bs-target="#checkinModal">
                        Tambah Check-in
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm mr-2" data-bs-toggle="modal"
                        data-bs-target="#checkoutModal">
                        Tambah Check-out
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#uploadModal">
                        Upload File
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="exportButton">Export to Excel</button>

                </div>
                <div class="mb-3">
                    <label for="monthFilter" class="form-label">Filter Bulan</label>
                    <select id="monthFilter" class="form-select">
                        <option value="">Pilih Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>

                </div>
                <table id="myTable" class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NPK</th>
                            <th>Tanggal</th>
                            <th>Waktu Check-in</th>
                            <th>Waktu Check-out</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk Check-in -->
    <div class="modal fade" id="checkinModal" tabindex="-1" aria-labelledby="checkinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkinModalLabel">Tambah Check-in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkinForm">
                        @csrf

                        <div class="form-group">
                            <label for="npk">NPK</label>
                            <input type="text" class="form-control" id="npk" name="npk" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="waktuci">Waktu Check-in</label>
                            <input type="time" class="form-control" id="waktuci" name="waktuci" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Check-out -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="checkoutModalLabel">Tambah Check-out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm">
                        @csrf

                        <div class="form-group">
                            <label for="npk">NPK</label>
                            <input type="text" class="form-control" id="npk" name="npk" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="waktuco">Waktu Check-out</label>
                            <input type="time" class="form-control" id="waktuco" name="waktuco" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                    <form id="uploadForm" method="POST" enctype="multipart/form-data" action="{{ route('upload') }}">
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
    <script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rekap.getData') }}",
                    type: 'GET',

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'kategorishift.nama'
                    },
                    {
                        data: 'npk',
                        name: 'npk'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'waktuci',
                        name: 'waktuci'
                    },
                    {
                        data: 'waktuco',
                        name: 'waktuco'
                    }
                ]
            });

            // Update table data when filter changes
            $('#monthFilter').on('change', function() {
                table.ajax.reload();
            });

            // Submit data Check-in
            $('#checkinForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('rekap.storeCheckin') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#checkinModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Submit data Check-out
            $('#checkoutForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('rekap.storeCheckout') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#checkoutModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#exportButton').on('click', function() {
                // Get selected month and year from filters
                var month = $('#monthFilter').val();
                var year = new Date().getFullYear(); // Assuming the year is the current year

                // Redirect to the export route with query parameters
                window.location.href = "{{ route('rekap.export') }}?month=" + month + "&year=" + year;
            });
        });
    </script>
@endsection

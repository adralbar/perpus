@extends('layout/main')

<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">



@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Data Absensi Karyawan</p>
                <div class="p-3 ml-3 text-black card">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                                data-bs-target="#checkinModal">
                                Tambah Check-in
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mr-2" data-bs-toggle="modal"
                                data-bs-target="#checkoutModal">
                                Tambah Check-out
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm mr-2" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                Upload File
                            </button>
                        </div>
                        <button type="button" class="btn btn-success btn-sm" id="exportButton">Export to Excel</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="startDate" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="endDate" class="form-control">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-light table-striped ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NPK Api</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Section</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Waktu Check-in</th>
                                    <th>Waktu Check-out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
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
                                <label for="npk">NPK Api</label>
                                <input type="text" class="form-control" id="npk" name="npk" list="npkList"
                                    required>
                                <datalist id="npkList">

                                </datalist>
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
                        <h5 class="modal-title" id="checkoutModalLabel">Tambah Check-out</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="checkoutForm">
                            @csrf
                            <div class="form-group">
                                <label for="npk">NPK Api</label>
                                <input type="text" class="form-control" id="npk" name="npk" list="npkList"
                                    required>
                                <datalist id="npkList">

                                </datalist>
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
                        <form id="uploadForm" method="POST" enctype="multipart/form-data"
                            action="{{ route('upload') }}">
                            @csrf
                            <div class="form-group">
                                <label for="file">Upload File</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".txt"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="penyimpanganModal" tabindex="-1" aria-labelledby="penyimpanganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penyimpanganModalLabel">Detail Penyimpangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="penyimpanganTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Tanggal Mulai</th>
                                <th>Jam Mulai</th>
                                <th>Kategori</th>
                                <th>Approved By</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                ajax: {
                    url: "{{ route('rekap.getData') }}",
                    type: 'GET',
                    data: function(d) {
                        d.startDate = $('#startDate').val(); // Ambil nilai tanggal mulai
                        d.endDate = $('#endDate').val(); // Ambil nilai tanggal selesai
                    }
                },
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
                        data: 'division_nama',
                        name: 'division_nama'
                    },
                    {
                        data: 'department_nama',
                        name: 'department_nama'
                    },
                    {
                        data: 'section_nama',
                        name: 'section_nama'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'shift1',
                        name: 'shift1'
                    },

                    {
                        data: 'waktuci',
                        name: 'waktuci'
                    },
                    {
                        data: 'waktuco',
                        name: 'waktuco'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ]
            });
            $(document).on('click', '.view-pelanggaran', function() {
                var npk = $(this).data('npk');
                var tanggal = $(this).data('tanggal');

                $.ajax({
                    url: "{{ route('getPenyimpangan') }}",
                    method: 'GET',
                    data: {
                        npk: npk,
                        tanggal: tanggal
                    },
                    success: function(response) {
                        var tableBody = $('#penyimpanganTable tbody');
                        tableBody.empty();
                        $.each(response.data, function(index, item) {
                            tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nama}</td>
                        <td>${item.keterangan}</td>
                        <td>${item.tanggal_mulai}</td>
                        <td>${item.jam_mulai}</td>
                        <td>${item.kategori}</td>
                        <td>${item.approved_by}</td>
                    </tr>
                `);
                        });
                        $('#penyimpanganModal').modal('show');
                    },
                    error: function() {
                        alert('Error fetching data.');
                    }
                });
            });




            // Update table data when filter changes (Tanggal Mulai atau Tanggal Selesai)
            $('#startDate, #endDate').on('change', function() {
                table.ajax.reload(); // Reload data berdasarkan rentang tanggal
            });
            // Update table data when filter changes


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

            // Export to Excel
            $('#exportButton').on('click', function() {
                // Ambil nilai filter bulan dan tahun dari elemen
                var startDate = $('#startDate').val(); // Pastikan ini adalah format yyyy-mm-dd
                var endDate = $('#endDate').val(); // Pastikan ini adalah format yyyy-mm-dd
                var search = $('#dt-search-0').val();
                // Redirect to the export route with query parameters
                window.location.href = "{{ route('rekap.export') }}?startDate=" + encodeURIComponent(
                        startDate) +
                    "&endDate=" + encodeURIComponent(endDate) +
                    "&search=" + encodeURIComponent(search);

            });
        })



        $(document).ready(function() {
            // Ambil data nama dan npk dari route shift.data
            $.ajax({
                url: '{{ route('shift.data') }}', // Ganti dengan route yang sesuai
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("Respons server:", response); // Debugging respons

                    // Cek apakah response memiliki properti 'data'
                    if (response.data && Array.isArray(response.data)) {
                        $.each(response.data, function(index, item) {
                            console.log("Menambahkan item:", item.npk, item
                                .nama); // Debugging tiap item
                            $('#npkList').append('<option value="' + item.npk + '">' + item
                                .nama + ' (' + item.npk + ')</option>');
                        });
                    } else {
                        console.error("Data tidak dalam format array atau tidak ditemukan.");
                    }
                },
                error: function() {
                    alert('Gagal mengambil data NPK');
                }
            });
        });
    </script>
    @if (session('success'))
        <script script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: "{{ session('success') }}",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
            });
        </script>
    @endif
@endsection

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
                                    <th>NPK Sistem</th>
                                    <th>NPK Api</th>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th>Departemen</th>
                                    <th>Section</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Waktu Check-in</th>
                                    <th>Waktu Check-out</th>
                                    <th>Status</th>
                                    <th>Api Time</th>
                                    <th>Aksi</th>
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
                                <small class="form-text text-muted">Maksimal ukuran file 250 KB.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="penyimpanganModal" tabindex="-1" aria-labelledby="penyimpanganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width: 90%; margin: auto; margin-top: 10%;">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penyimpanganModalLabel">Detail Penyimpangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="overflow-x: auto;">
                    <table class="table table-bordered table-striped table-hover" id="penyimpanganTable"
                        style="width: 100%; table-layout: auto;">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Kondisi Absen</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Keterangan</th>
                                <th>Foto</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="penyimpanganModal" tabindex="-1" aria-labelledby="penyimpanganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="penyimpanganModalLabel">Detail Penyimpangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="penyimpanganTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Kondisi Absen</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Keterangan</th>
                                    <th>Foto</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>






    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="npkEdit">NPK</label>
                            <input type="text" class="form-control" id="npkEdit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tanggalEdit">Tanggal</label>
                            <input type="text" class="form-control" id="tanggalEdit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="waktuciEdit">Waktu CheckIn</label>
                            <input type="time" class="form-control" id="waktuciEdit">
                        </div>
                        <div class="form-group">
                            <label for="waktucoEdit">Waktu CheckOut</label>
                            <input type="time" class="form-control" id="waktucoEdit">
                        </div>
                        <div class="form-group">
                            <label for="namaEdit">Nama</label>
                            <input type="text" class="form-control" id="namaEdit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="sectionEdit">Section</label>
                            <input type="text" class="form-control" id="sectionEdit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="departmentEdit">Department</label>
                            <input type="text" class="form-control" id="departmentEdit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="divisionEdit">Division</label>
                            <input type="text" class="form-control" id="divisionEdit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="shiftEdit">Shift</label>
                            <input type="text" class="form-control" id="shiftEdit" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary btnedit" id="saveChanges">Simpan Perubahan</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Lihat Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Foto" class="img-fluid">
                </div>
            </div>
        </div>
    </div>



    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
    <script>
        window.onload = function() {
            alert('Silahkan isi Filter tanggal terlebih dahulu!');
        };
        document.getElementById('uploadForm').onsubmit = function(e) {
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];

            if (file.size > 250 * 1024) { // 250 KB
                alert('File size must not exceed 250 KB.');
                e.preventDefault(); // Mencegah form dari pengiriman
            }
        };
        $(document).ready(function() {

            function loadDataTable() {
                var table = $('#myTable').DataTable({
                    processing: true,
                    serverSide: false,
                    paging: false,
                    ajax: {
                        url: "{{ route('rekap.getData') }}",
                        type: 'GET',
                        data: function(d) {
                            d.startDate = $('#startDate').val();
                            d.endDate = $('#endDate').val();
                        },
                        error: function(xhr, error, code) {
                            console.error("Error occurred while fetching data:", xhr.responseText);
                        }
                    },
                    columns: [{
                            data: null, // Set data menjadi null untuk kolom nomor urut
                            orderable: false, // Nonaktifkan pengurutan untuk kolom ini
                            searchable: false // Nonaktifkan pencarian untuk kolom ini
                        },
                        {
                            data: 'npk_sistem',
                            name: 'npk_sistem'
                        },
                        {
                            data: 'npk',
                            name: 'npk'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
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
                        {
                            data: 'api_time',
                            name: 'api_time',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'aksi',
                            name: 'aksi'
                        }
                    ],
                    order: [
                        [6, 'asc']
                    ],

                    rowCallback: function(row, data, index) {
                        // Mengupdate nomor urut di sel pertama
                        $('td:eq(0)', row).html(index + 1); // Menampilkan nomor urut
                    }
                });
            }


            $('#startDate, #endDate').on('change', function() {
                if ($('#startDate').val() && $('#endDate').val()) {
                    if ($.fn.dataTable.isDataTable('#myTable')) {
                        table.destroy();
                        table.ajax.reload();
                    }
                    loadDataTable();
                } else {
                    if ($.fn.dataTable.isDataTable('#myTable')) {
                        table.clear().draw();
                    }
                }
            });

            $('#exportButton').on('click', function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var search = $('#dt-search-0').val();
                window.location.href = "{{ route('rekap.export') }}?startDate=" + encodeURIComponent(
                        startDate) +
                    "&endDate=" + encodeURIComponent(endDate) +
                    "&search=" + encodeURIComponent(search);

            });


            $('#checkinForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('rekap.storeCheckin') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log("Success response: ", response);
                        $('#checkinModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        console.error("Error response: ", xhr.responseText);
                    }
                });
            });

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



        function showImage(imageUrl) {
            // Set the image source
            document.getElementById('modalImage').src = imageUrl;

            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('photoModal'));
            myModal.show();
        }
        $(document).on('click', '.view-penyimpangan', function() {
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
                        <td>${item.kategori}</td>
                        <td>${item.jenis_keperluan || ''}</td>
                        <td>${item.tanggal_mulai}</td>
                        <td>${item.tanggal_selesai || ''}</td>
                        <td>${item.jam_mulai|| ''}</td>
                        <td>${item.jam_selesai || ''}</td>
                        <td>${item.keterangan}</td>
                        <td>${item.file_upload || ''}</td>
                        <td>${item.approved_by|| ''}</td>
                    </tr>
                `);
                    });
                    $('#penyimpanganModal').modal('show');
                },
                error: function() {
                    alert('Error fetching data.');
                }
            });
        })



        $(document).ready(function() {
            $.ajax({
                url: '{{ route('shift.data') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log("Respons server:", response);

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

        //edit data
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            console.log(id);
            $.ajax({
                url: '/get-data',
                method: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    $('#npkEdit').val(response.npk);
                    $('#tanggalEdit').val(response.tanggal);
                    $('#waktuciEdit').val(response.waktuci);
                    $('#waktucoEdit').val(response.waktuco);
                    $('#namaEdit').val(response.nama);
                    $('#sectionEdit').val(response.section_nama);
                    $('#departmentEdit').val(response.department_nama);
                    $('#divisionEdit').val(response.division_nama);
                    $('#shiftEdit').val(response.shift1);
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });


            $('#saveChanges').click(function() {
                var npk = $('#npkEdit').val();
                var tanggal = $('#tanggalEdit').val();
                var waktuci = $('#waktuciEdit').val();
                var waktuco = $('#waktucoEdit').val();

                $.ajax({
                    url: `/update-data/${npk}/${tanggal}`,
                    method: 'POST',
                    data: {
                        waktuci: waktuci,
                        waktuco: waktuco,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#editModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert("Gagal memperbarui data.");
                    }
                });
            });
        });
    </script>
    @if (session('success'))
        <script>
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

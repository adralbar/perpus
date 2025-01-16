@extends('layout/main')


<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">

<link rel="stylesheet" href="{{ asset('dist/css/daterangepicker.css') }}">
<link rel="stylesheet" type="text/css">

@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Shift Karyawan</p>
            <div class="p-3 ml-3 text-black card">
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
                <div class="text-center mb-3">
                    <button id="loadDataBtn" type="button" class="btn btn-primary btn-sm" style="border-radius: 5px;">
                        Tampilkan Data
                    </button>
                </div>
                <div class="mb-3">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="myTable">
                                <thead class="table-light">
                                    <tr id="dynamicHeaders">
                                        <th>No</th>
                                        <th>Departemen</th>
                                        <th>Tanggal</th>
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
    </div>
    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>

    <script>
        window.onload = function() {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Silahkan isi Filter tanggal terlebih dahulu!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        };
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('rekapshiftdata') }}", // Route untuk mengambil data
                    data: function(d) {
                        d.startDate = $('#startDate').val(); // Filter tanggal
                        d.endDate = $('#endDate').val();
                    },
                    dataSrc: function(json) {
                        // Ambil shift_name dari hasil data untuk membuat header dinamis
                        let shiftNames = [];
                        json.forEach(row => {
                            Object.keys(row.shiftcount).forEach(shiftName => {
                                if (!shiftNames.includes(shiftName)) {
                                    shiftNames.push(shiftName);
                                }
                            });
                        });

                        // Tambahkan header dinamis ke tabel
                        var headers = $('#dynamicHeaders');
                        headers.find('th:gt(2)').remove(); // Hapus header shift lama
                        shiftNames.forEach(shiftName => {
                            headers.append('<th>' + shiftName + '</th>');
                        });

                        return json; // Kembalikan data untuk DataTables
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'department_nama',
                        name: 'department_nama'
                    }, // Departemen
                    {
                        data: 'date',
                        name: 'date'
                    }, // Tanggal

                ],
                rowCallback: function(row, data, index) {
                    // Tambahkan nomor urut
                    $('td:eq(0)', row).html(index + 1);

                    // Tambahkan kolom dinamis untuk setiap shift
                    let shiftCounts = data.shiftcount;
                    Object.keys(shiftCounts).forEach(shiftName => {
                        $(row).append('<td>' + shiftCounts[shiftName] + '</td>');
                    });
                }
            });

            // Reload data ketika tombol ditekan
            $('#loadDataBtn').click(function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection

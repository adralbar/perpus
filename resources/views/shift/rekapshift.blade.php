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
                <div class="mb-3">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="myTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Shift</th>
                                        <th>Jumlah Shift</th>
                                        <th>Jumlah NPK</th>
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
        $(document).ready(function() {
            $('#myTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('rekapshiftdata') }}",
                    dataSrc: '', // Data is already in array format

                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'shift1',
                        name: 'shift1'
                    },
                    {
                        data: 'shiftcount',
                        name: 'shiftcount'
                    },
                    {
                        data: 'npkCount',
                        name: 'npkCount'
                    },
                ],
                rowCallback: function(row, data, index) {
                    $('td:eq(0)', row).html(index + 1);
                }
            });
        });
    </script>
@endsection

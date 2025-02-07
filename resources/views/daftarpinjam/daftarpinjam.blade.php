@extends('layout/main')
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Daftar Pinjam</p>
                <div class="p-3 ml-3 text-black card">


                    <div class="table-responsive">
                        <table class="table table-bordered" id="bukuTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                    @if (Auth::user()->role_id == 1)
                                        <!-- Menampilkan kolom email dan nama jika role_id = 1 -->
                                        <th>Email Peminjam</th>
                                        <th>Nama Peminjam</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data buku akan ditampilkan di sini melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
        <script src="{{ asset('lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script> <!-- Dual Listbox -->
        </script>
        <script>
            $(document).ready(function() {
                // Set CSRF token untuk header AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Inisialisasi DataTable
                var table = $('#bukuTable').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: '/daftarpinjam/getbuku',
                        type: 'GET',
                        dataSrc: function(json) {
                            // Tambahkan data pagination (seperti current_page dan total_pages)
                            json.recordsTotal = json.total_pages;
                            return json.data;
                        }
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'judul'
                        },
                        {
                            data: 'penulis'
                        },
                        {
                            data: 'penerbit'
                        },
                        {
                            data: 'tanggal'
                        },
                        {
                            data: 'status',
                            render: function(data) {
                                return data == 0 ? 'Belum Kembali' : 'Sudah Kembali';
                            }
                        },
                        {
                            data: 'foto',
                            render: function(data) {
                                return `<img src="${data}" alt="Foto Buku" width="100" height="auto">`;
                            }
                        },
                        @if (Auth::user()->role_id == 1)
                            {
                                data: 'email'
                            }, {
                                data: 'nama'
                            }, {
                                data: 'status',
                                render: function(data, type, row) {
                                    // Menambahkan checkbox untuk mengubah status
                                    var checked = data == 1 ? 'checked disabled' :
                                        ''; // Jika status = 1, disable checkbox
                                    return `<input type="checkbox" class="change-status" data-id="${row.id}" ${checked}>`;
                                }
                            }
                        @endif
                    ]
                });

                // Event listener untuk tombol centang
                $('#bukuTable').on('change', '.change-status', function() {
                    var bukuId = $(this).data('id');
                    var status = $(this).prop('checked') ? 1 :
                        0; // Jika centang, status = 1; jika tidak, status = 0

                    // Update status di server
                    $.ajax({
                        url: '/daftarpinjam/update/' + bukuId,
                        method: 'PUT',
                        data: {
                            status: status
                        },
                        success: function(response) {
                            // Jika status berhasil diperbarui, beri notifikasi atau update DataTable jika perlu
                            table.ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            alert('Gagal memperbarui status!');
                        }
                    });
                });
            });
        </script>
    @endsection

@extends('layout/main')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Dashboard</p>
            <div style="background-color: white; margin:20px; width:1200px;height:400px">
                <canvas id="myChart" style="padding: 20px; width: 100%; height: 100%;"></canvas>
            </div>
        </div>

        <!-- Tabel berdampingan dengan margin yang sama seperti chart -->
        <div class="p-3" style="margin: 20px;">
            <div class="row">
                <!-- Tabel 1 -->
                <div class="">
                    <div style="background-color: white; padding: 15px;">
                        <table id="table1" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NPK</th>
                                    <th>Bulan</th>
                                    <th>Total Keterlambatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk detail keterlambatan -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Keterlambatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nama :</strong> <span id="detailNama"></span></p>
                        <p><strong>NPK :</strong> <span id="detailNpk"></span></p>
                        <p><strong>Total Keterlambatan :</strong> <span id="detailTotal"></span> kali</p>
                        <div id="detailTanggalWaktu"></div> <!-- Tempat untuk menampilkan tanggal dan waktu -->
                        <div id="detailKeterlambatan"></div> <!-- Tempat untuk menampilkan tanggal dan waktu -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        @include('dashboard.script ')
    @endsection

@extends('layout/main')
<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Dashboard</p>

            <!-- Filter Tahun -->
            <div class="d-flex align-items-center mb-3">
                <select id="filterYear" class="form-select" style="width: 150px; margin-right: 20px; margin-left: 20px">
                    <option value="">Pilih Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-light border btnReset fw-normal" data-toggle="modal"
                    data-target="#checkinModal">
                    Reset chart & table
                </button>
            </div>




            <div
                style="background-color: #343a40; margin:20px; width:1200px;height:400px; border-radius: 8px; overflow: hidden;">
                <canvas id="myChart" style="padding: 20px; width: 100%; height: 100%;"></canvas>
            </div>

        </div>

        <!-- Tabel berdampingan dengan margin yang sama seperti chart -->
        <div class="p-3" style="margin: 20px;">
            <div class="row">
                <div class="">
                    <div style="padding: 15px;" class="card">
                        <table id="table1" class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NPK</th>
                                    <th>Tahun</th>
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
                        <div id="detailTanggalWaktu"></div>
                        <div id="detailKeterlambatan"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


        @include('dashboard.script')
    @endsection

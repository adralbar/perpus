@extends('layout/main')

@section('style')
    <link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Dashboard</p>

            <div class="d-flex align-items-center mb-3">
                <select id="filterYear" class="form-select" style="width: 150px; margin-right: 20px;  margin-left: 20px;">
                    <option value="">Pilih Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>

            </div>
            <!-- Chart Area -->
            <div class="chart-container" style="margin: 20px; border-radius: 8px; overflow: hidden;">
                <canvas id="myChart" style="width: 100%; height: 400px; background-color: #ffffff;"></canvas>
            </div>
        </div>

        <div class="p-3" style="margin: 20px;">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table1" class="table table-light table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NPK Api</th>
                                            <th>Divisi</th>
                                            <th>Departemen</th>
                                            <th>Section</th>
                                            <th>Tahun</th>
                                            <th>Total Keterlambatan</th>
                                            <th>Aksi</th>
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

        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Keterlambatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nama:</strong> <span id="detailNama"></span></p>
                        <p><strong>NPK:</strong> <span id="detailNpk"></span></p>
                        <p><strong>Total Keterlambatan:</strong> <span id="detailTotal"></span></p>
                        <div id="detailTanggalWaktu"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('dist/js/plugins/chart.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
    <script>
        let table1;
        let selectedMonth = '';



        $(document).ready(function() {
            const ctx = document.getElementById('myChart').getContext('2d');
            let myChart;

            function loadChartData(year) {
                $.ajax({
                    url: '{{ route('data.chart') }}',
                    type: 'GET',
                    data: {
                        year: year
                    },
                    success: function(response) {
                        const labels = response.labels || [];
                        const totals = response.totals || [];
                        const targetData = Array(labels.length).fill(0.8); // Nilai target 0.8%

                        // Debugging data labels
                        console.log("Labels received:", labels); // Log untuk melihat data labels

                        if (myChart) myChart.destroy(); // Hancurkan chart sebelumnya jika ada

                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Total keterlambatan',
                                        backgroundColor: '#3f6791',
                                        data: totals,
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Target 0.8%',
                                        type: 'line',
                                        data: targetData,
                                        borderColor: '#ff6347',
                                        backgroundColor: 'rgba(255, 99, 71, 0.2)',
                                        borderWidth: 2,
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        grid: {
                                            drawBorder: false,
                                            borderDash: [5, 5]
                                        }
                                    }
                                },
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        const index = elements[0]
                                            ._index; // Mengakses index dari elemen pertama
                                        const selectedMonthName = this.data.labels[
                                            index]; // Nama bulan berdasarkan index

                                        console.log("Elements clicked:", elements);
                                        console.log("Clicked element index:", index);
                                        console.log("Selected month name:",
                                            selectedMonthName);

                                        const monthMap = {
                                            'January': '01',
                                            'Jan': '01',
                                            'February': '02',
                                            'Feb': '02',
                                            'March': '03',
                                            'Mar': '03',
                                            'April': '04',
                                            'Apr': '04',
                                            'May': '05',
                                            'Jun': '06',
                                            'June': '06',
                                            'July': '07',
                                            'Jul': '07',
                                            'August': '08',
                                            'Aug': '08',
                                            'September': '09',
                                            'Sep': '09',
                                            'October': '10',
                                            'Oct': '10',
                                            'November': '11',
                                            'Nov': '11',
                                            'December': '12',
                                            'Dec': '12'
                                        };

                                        if (selectedMonthName && monthMap[
                                                selectedMonthName]) {
                                            const selectedMonth = monthMap[
                                                selectedMonthName];
                                            console.log("Mapped month:", selectedMonth);
                                            loadDataPerTanggal(selectedMonth, year);
                                        } else {
                                            console.error("Month not found in monthMap:",
                                                selectedMonthName);
                                        }
                                    } else {
                                        console.log("No element clicked");
                                    }
                                }
                            }
                        });
                    },
                    error: function(error) {
                        console.error('Error loading chart data:', error);
                    }
                });
            }

            function loadDataPerTanggal(month, year) {
                console.log("Loading data for month:", month, "and year:",
                    year); // Cek apakah bulan dan tahun yang dikirim sudah benar

                $.ajax({
                    url: '{{ route('data.perTanggal') }}',
                    type: 'GET',
                    data: {
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        console.log(response); // Memeriksa respons yang diterima

                        const labels = response.days || []; // Mengambil 'days' sebagai label
                        const totals = response.totals ||
                    []; // Mengambil 'totals' sebagai data keterlambatan

                        const targetData = Array(labels.length).fill(0.8); // Nilai target 0.8%

                        if (myChart) myChart.destroy(); // Hancurkan chart sebelumnya jika ada

                        // Membuat chart baru dengan data per tanggal
                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                        label: 'Total Keterlambatan',
                                        backgroundColor: '#3f6791',
                                        data: totals,
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'Target 0.8%',
                                        type: 'line',
                                        data: targetData,
                                        borderColor: '#ff6347',
                                        backgroundColor: 'rgba(255, 99, 71, 0.2)',
                                        borderWidth: 2,
                                        tension: 0.4
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        grid: {
                                            drawBorder: false,
                                            borderDash: [5, 5]
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            }


            // Muat data chart saat halaman pertama kali dimuat
            const initialYear = $('#filterYear').val();
            loadChartData(initialYear);

            // Perbarui data chart jika filter tahun berubah
            $('#filterYear').change(function() {
                const selectedYear = $(this).val();
                loadChartData(selectedYear);
            });
        });





        // document.getElementById('filterYear').addEventListener('change', function() {
        //     const selectedYear = this.value;
        //     if (selectedYear) {
        //         for (let month in monthMap) {
        //             monthMap[month] = `${selectedYear}-${monthMap[month].split('-')[1]}`;
        //             console.log(monthMap[month]);
        //         }
        //     }
        // });

        //Function to load chart data

        // function loadChartData(year) {
        //     $.ajax({
        //         url: '{{ route('data.chart') }}',
        //         type: 'GET',
        //         data: {
        //             year: year
        //         },
        //         success: function(response) {
        //             myChart.data.labels = response.labels;
        //             myChart.data.datasets[0].data = response.totals;
        //             myChart.update();
        //         }
        //     });
        // }


        // $(document).ready(function() {
        //     table1 = $('#table1').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         ajax: {
        //             url: '{{ route('data.table1b') }}',
        //             type: 'GET',
        //             data: function(d) {
        //                 d.tahun = $('#filterYear').val();
        //             },
        //         },
        //         columns: [{
        //                 data: 'DT_RowIndex',
        //                 orderable: false,
        //                 searchable: false
        //             },
        //             {
        //                 data: 'nama',
        //                 name: 'nama'
        //             },
        //             {
        //                 data: 'npk',
        //                 name: 'npk'
        //             },
        //             {
        //                 data: 'division_nama',
        //                 name: 'division_nama'
        //             },
        //             {
        //                 data: 'department_nama',
        //                 name: 'department_nama'
        //             },
        //             {
        //                 data: 'section_nama',
        //                 name: 'section_nama'
        //             },
        //             {
        //                 data: 'tahun',
        //                 name: 'tahun'
        //             },
        //             {
        //                 data: 'total_keterlambatan',
        //                 name: 'total_keterlambatan'
        //             },
        //             {
        //                 data: 'aksi',
        //                 orderable: false,
        //                 searchable: false
        //             }
        //         ]
        //     });

        //     $('#table1').on('click', '.btnDetail', function(e) {
        //         e.preventDefault();

        //         var nama = $(this).data('nama');
        //         var npk = $(this).data('npk');
        //         var total = $(this).data('total');
        //         var tanggal = $(this).data('tanggal');
        //         var waktu = $(this).data('waktu');
        //         var shift1 = $(this).data('shift1');

        //         var tanggalList = tanggal.split(',');
        //         var waktuList = waktu.split(',');
        //         var shiftList = shift1.split(',');

        //         var detailHtml = '';

        //         for (var i = 0; i < tanggalList.length; i++) {
        //             var shift = shiftList[i] ? shiftList[i].trim() : '';
        //             var absensiTime = waktuList[i].trim(); // Ambil waktu masuk

        //             var shiftStartTimeStr = shift.split(' - ')[0].replace('.', ':'); // Ubah '.' menjadi ':'
        //             shiftStartTimeStr += ':00';
        //             var shiftStartTime = new Date('1970-01-01T' + shiftStartTimeStr +
        //                 'Z'); // Ubah ke format Date

        //             var absensiDate = new Date('1970-01-01T' + absensiTime + 'Z'); // Waktu absensi

        //             // Debug untuk melihat nilai shiftStartTime dan absensiDate
        //             console.log("Shift Start Time: ", shiftStartTime);
        //             console.log("Absensi Time: ", absensiDate);

        //             // Menghitung selisih dalam menit
        //             var selisihWaktu = Math.floor((absensiDate - shiftStartTime) / 60000);

        //             // Jika selisih waktu negatif, artinya tidak terlambat
        //             selisihWaktu = selisihWaktu > 0 ? selisihWaktu : 0;

        //             // Debug untuk melihat nilai selisihWaktu
        //             console.log("Selisih Waktu: ", selisihWaktu);

        //             detailHtml += `
    //     <div style="margin-bottom: 10px; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
    //         <strong>Tanggal:</strong> ${tanggalList[i]}<br>
    //         <strong>Waktu In:</strong> ${waktuList[i]}<br>
    //         <strong>Shift:</strong> ${shift}<br>
    //         <strong>Keterlambatan:</strong> ${selisihWaktu} menit
    //     </div>`;
        //         }

        //         $('#detailNama').text(nama);
        //         $('#detailNpk').text(npk);
        //         $('#detailTotal').text(total);
        //         $('#detailTanggalWaktu').html(detailHtml);
        //         $('#detailModal').modal('show');
        //     });
        // });






        // $('#filterYear').change(function() {
        //     var year = $(this).val();

        //     loadChartData(year);
        //     if (table1) {
        //         table1.ajax.url('{{ route('data.table1b') }}?tahun=' + encodeURIComponent(year)).load();
        //     }
        // });





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
                 `,
            });
        @endif
    </script>
@endsection

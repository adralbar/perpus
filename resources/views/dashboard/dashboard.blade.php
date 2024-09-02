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
        <script src="{{ asset('dist/js/plugins/chart.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>


        <script>
            const monthMap = {
                'Jan': '2025-01',
                'Feb': '2025-02',
                'Mar': '2025-03',
                'Apr': '2025-04',
                'May': '2025-05',
                'Jun': '2025-06',
                'Jul': '2025-07',
                'Aug': '2025-08',
                'Sep': '2025-09',
                'Oct': '2025-10',
                'Nov': '2025-11',
                'Dec': '2025-12'
            };
            document.getElementById('filterYear').addEventListener('change', function() {
                const selectedYear = this.value;
                if (selectedYear) {
                    for (let month in monthMap) {
                        monthMap[month] = `${selectedYear}-${monthMap[month].split('-')[1]}`;
                        console.log(monthMap[month]);
                    }
                }
            });

            // Function untuk load data 
            function loadChartData(year) {
                $.ajax({
                    url: '{{ route('data.chart') }}',
                    type: 'GET',
                    data: {
                        year: year
                    },
                    success: function(response) {
                        myChart.data.labels = response.labels;
                        myChart.data.datasets[0].data = response.totals;
                        myChart.update();
                    }
                });
            }

            //konfigurasi chart
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Total keterlambatan',
                        backgroundColor: '#3f6791',
                        data: @json($totals),
                        borderWidth: 1
                    }]
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
                            max: 10,
                            grid: {
                                drawBorder: false,
                                borderDash: [5, 5]
                            }
                        }
                    },
                    onClick: function(event, elements) {
                        if (elements.length > 0) {
                            var index = elements[0].index;
                            var selectedMonthName = this.data.labels[index]; // Ambil nama bulan
                            var selectedMonth = monthMap[selectedMonthName] ||
                                ''; // Konversi nama bulan menjadi nomor bulan (01, 02, dll)
                            var year = $('#filterYear').val(); // Ambil tahun yang dipilih

                            var formattedMonth = selectedMonth; // Format menjadi YEAR-MONTH

                            var url1 = '{{ route('data.table1b') }}' +
                                '?bulan=' + encodeURIComponent(formattedMonth) +
                                '&tahun=' + encodeURIComponent(year);

                            console.log('Selected Month Name:', selectedMonthName);
                            console.log('Selected Month:', selectedMonth);
                            console.log('Formatted Month:', formattedMonth);
                            console.log('Year:', year);
                            console.log('Request URL:', url1);

                            table1.ajax.url(url1).load(); // Memuat ulang DataTables dengan URL baru
                        }
                    }

                }
            });

            var table1 = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('data.table1') }}',
                    type: 'GET',
                    data: function(d) {
                        d.tahun = $('#filterYear').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'npk'
                    },
                    {
                        data: 'tahun'
                    },
                    {
                        data: 'total_keterlambatan'
                    },
                    {
                        data: 'aksi'
                    }
                ]
            });


            $(document).ready(function() {
                $('#table1').on('click', '.btnDetail', function(e) {
                    e.preventDefault();
                    var nama = $(this).data('nama');
                    var npk = $(this).data('npk');
                    var total = $(this).data('total');
                    var tanggal = $(this).data('tanggal');
                    var waktu = $(this).data('waktu');

                    var tanggalList = tanggal.split(',');
                    var waktuList = waktu.split(',');
                    var detailHtml = '';

                    for (var i = 0; i < tanggalList.length; i++) {
                        detailHtml +=
                            `<p><strong>Tanggal:</strong> ${tanggalList[i]}, <strong>Waktu:</strong> ${waktuList[i]}</p>`;
                    }

                    $('#detailNama').text(nama);
                    $('#detailNpk').text(npk);
                    $('#detailTotal').text(total);
                    $('#detailTanggalWaktu').html(detailHtml);
                    $('#detailModal').modal('show');
                });

                $('#filterYear').change(function() {
                    var year = $(this).val();
                    loadChartData(year); // Reload chart data based on the selected year
                    table1.ajax.reload(); // Reload DataTable data based on the selected year
                });
            });


            function resetChartAndTable() {
                // Reset chart data
                myChart.data.labels = @json($labels); // Set the initial labels
                myChart.data.datasets[0].data = @json($totals); // Set the initial data
                myChart.update(); // Update the chart

                // Reset DataTable
                $('#filterYear').val(''); // Clear the year filter dropdown
                table1.ajax.url('{{ route('data.table1') }}').load(); // Reload DataTable with default URL and parameters
            }

            $(document).ready(function() {
                // Event handler for reset button
                $('.btnReset').on('click', function() {
                    resetChartAndTable(); // Call the reset function
                });
            });
        </script>

        @include('dashboard.script')
    @endsection

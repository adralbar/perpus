@extends('layout/main')

@section('style')
    <link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="p-3">
            <p class="pl-3 pb-3 font-weight-bold h3">Dashboard</p>

            <!-- Filter Tahun -->
            <div class="d-flex align-items-center mb-3">
                <select id="filterYear" class="form-select" style="width: 150px; margin-right: 20px;">
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

            <!-- Chart Area -->
            <div class="chart-container" style="margin: 20px; border-radius: 8px; overflow: hidden;">
                <canvas id="myChart" style="width: 100%; height: 400px; background-color: #343a40;"></canvas>
            </div>
        </div>

        <!-- Tabel berdampingan dengan margin yang sama seperti chart -->
        <div class="p-3" style="margin: 20px;">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table1" class="table table-dark table-striped">
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
                                        <!-- Data akan diisi melalui AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk detail keterlambatan -->
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

        document.getElementById('filterYear').addEventListener('change', function() {
            const selectedYear = this.value;
            if (selectedYear) {
                for (let month in monthMap) {
                    monthMap[month] = `${selectedYear}-${monthMap[month].split('-')[1]}`;
                    console.log(monthMap[month]);
                }
            }
        });

        // Function to load chart data
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

        // Configure chart
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels), // Chart labels, usually month names or abbreviations
                datasets: [{
                    label: 'Total keterlambatan',
                    backgroundColor: '#3f6791',
                    data: @json($totals), // Data points corresponding to each label
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
                        max: 10, // Adjust this as needed
                        grid: {
                            drawBorder: false,
                            borderDash: [5, 5]
                        }
                    }
                },
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index; // Get the index of the clicked bar
                        const selectedMonthName = this.data.labels[
                            index]; // Get the label (month name or abbreviation) of the clicked bar

                        // Map both full and abbreviated month names to numeric values
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
                            'May': '05',
                            'June': '06',
                            'Jun': '06',
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

                        const selectedMonth = monthMap[selectedMonthName] ||
                            ''; // Convert month name to numeric format
                        const year = $('#filterYear').val(); // Get the year from the filter input

                        const url1 = '{{ route('data.table1b') }}' + // Build the URL for the AJAX request
                            '?bulan=' + encodeURIComponent(selectedMonth) +
                            '&tahun=' + encodeURIComponent(year);

                        console.log('Selected Month Name:', selectedMonthName);
                        console.log('Selected Month:', selectedMonth);
                        console.log('Formatted Month:', selectedMonth);
                        console.log('Year:', year);
                        console.log('Request URL:', url1);

                        if (typeof table1 !== 'undefined') { // Check if table1 is defined
                            table1.ajax.url(url1).load(); // Update DataTable with new URL and reload data
                        } else {
                            console.error('table1 is not defined.');
                        }
                    }
                }
            }
        });



        $(document).ready(function() {
            table1 = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('data.table1') }}',
                    type: 'GET',
                    data: function(d) {
                        d.tahun = $('#filterYear').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
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
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'total_keterlambatan',
                        name: 'total_keterlambatan'
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table1').on('click', '.btnDetail', function(e) {
                e.preventDefault();
                var nama = $(this).data('nama');
                var npk = $(this).data('npk');
                var total = $(this).data('total');
                var tanggal = $(this).data('tanggal');
                var waktu = $(this).data('waktu');
                var shift1 = $(this).data('shift1');

                var tanggalList = tanggal.split(',');
                var waktuList = waktu.split(',');
                var detailHtml = '';

                // Calculate the time difference in minutes
                function calculateTimeDifference(shift, absensi) {
                    var shiftTimes = shift.split(' - ');
                    var shiftStart = new Date('1970-01-01T' + shiftTimes[0] + 'Z');
                    var absensiTime = new Date('1970-01-01T' + absensi + 'Z');

                    return Math.floor((absensiTime - shiftStart) / 60000);
                }

                for (var i = 0; i < tanggalList.length; i++) {
                    var selisihWaktu = calculateTimeDifference(shift1, waktuList[i]);
                    detailHtml += `
                 <div style="margin-bottom: 10px; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                     <strong>Tanggal:</strong> ${tanggalList[i]}<br>
                     <strong>Waktu In:</strong> ${waktuList[i]}<br>
                     <strong>Keterlambatan:</strong> ${selisihWaktu} menit
                 </div>`;
                }

                $('#detailNama').text(nama);
                $('#detailNpk').text(npk);
                $('#detailTotal').text(total);
                $('#detailTanggalWaktu').html(detailHtml);
                $('#detailModal').modal('show');
            });




            $('#filterYear').change(function() {
                var year = $(this).val();

                // Muat data chart dengan tahun yang baru
                loadChartData(year);

                // Reset tabel untuk menampilkan data tahun yang dipilih
                if (table1) {
                    table1.ajax.url('{{ route('data.table1') }}?tahun=' + encodeURIComponent(year)).load();
                }
            });


            $('.btnReset').on('click', function() {
                resetChartAndTable();
            });
        });

        function resetChartAndTable() {
            myChart.data.labels = @json($labels);
            myChart.data.datasets[0].data = @json($totals);
            myChart.update();

            $('#filterYear').val('');
            if (table1) {
                table1.ajax.url('{{ route('data.table1') }}').load();
            }
        }

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
                 `, // Tampilkan semua pesan error dalam bentuk list
            });
        @endif
    </script>
@endsection

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

                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel 2 -->
                {{-- <div class="">
                    <div style="background-color: white; padding: 15px;">
                        <table id="table2" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NPK</th>
                                    <th>Tanggal</th>
                                    <th>Keterlambatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Map dari nama bulan ke format YYYY-MM
        const monthMap = {
            'Jan': '2024-01',
            'Feb': '2024-02',
            'Mar': '2024-03',
            'Apr': '2024-04',
            'May': '2024-05',
            'Jun': '2024-06',
            'Jul': '2024-07',
            'Aug': '2024-08',
            'Sep': '2024-09',
            'Oct': '2024-10',
            'Nov': '2024-11',
            'Dec': '2024-12'
        };

        // Inisialisasi Chart.js
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Total keterlambatan',
                    backgroundColor: '#007bff',
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
                            display: false,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 200,
                        grid: {
                            drawBorder: false,
                            borderDash: [5, 5],
                        }
                    }
                },
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        console.log('Chart clicked, elements:', elements);
                        var index = elements[0].index;
                        var selectedMonthName = this.data.labels[index];
                        var selectedMonth = monthMap[selectedMonthName] || ''; // Ambil format bulan

                        var url1 = '{{ route('data.table1') }}' + '?bulan=' + encodeURIComponent(selectedMonth);
                        // var url2 = '{{ route('data.table2') }}' + '?bulan=' + encodeURIComponent(selectedMonth);

                        console.log('Updated URL for table1:', url1);
                        // console.log('Updated URL for table2:', url2);

                        // Update URL dan refresh data untuk table1
                        table1.ajax.url(url1).load(function(json) {
                            console.log('Data for table1 loaded:', json);


                        });
                    } else {
                        console.log('No element clicked.');
                    }
                }


            }
        });

        // Inisialisasi DataTable
        var table1 = $('#table1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('data.table1') }}',
                type: 'GET',

            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
                    data: 'bulan'
                },
                {
                    data: 'total_keterlambatan'
                }
            ]
        });


        // var table2 = $('#table2').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         url: '{{ route('data.table2') }}',
        //         type: 'GET',
        //     },
        //     columns: [{
        //             data: 'DT_RowIndex',
        //             name: 'DT_RowIndex',
        //             orderable: false,
        //             searchable: false
        //         },
        //         {
        //             data: 'nama'
        //         },
        //         {
        //             data: 'npk'
        //         },
        //         {
        //             data: 'bulan'
        //         },
        //         {
        //             data: 'total_keterlambatan'
        //         },
        //         {
        //             data: 'total_keterlambatan_menit'
        //         }
        //     ]
        // });
    </script>
@endsection

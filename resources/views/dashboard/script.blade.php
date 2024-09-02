<script src="{{ asset('dist/js/plugins/chart.js') }}"></script>
<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>


<script>
    const monthMap = {
        'January': '2025-01',
        'February': '2025-02',
        'March': '2025-03',
        'April': '2025-04',
        'May': '2025-05',
        'June': '2025-06',
        'July': '2025-07',
        'August': '2025-08',
        'September': '2025-09',
        'October': '2025-10',
        'November': '2025-11',
        'December': '2025-12'
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
                    max: 200,
                    grid: {
                        drawBorder: false,
                        borderDash: [5, 5]
                    }
                }
            },
            onClick: function(event, elements) {
                if (elements.length > 0) {
                    var index = elements[0].index;
                    var selectedMonthName = this.data.labels[index];
                    var selectedMonth = monthMap[selectedMonthName] || '';
                    var year = $('#filterYear').val(); // Ambil tahun yang dipilih

                    console.log('Selected Month:', selectedMonth); // Debugging selected month
                    console.log('Selected Year:', year); // Debugging selected year

                    var url1 = '{{ route('data.table1') }}' +
                        '?bulan=' + encodeURIComponent(selectedMonth) +
                        '&tahun=' + encodeURIComponent(year);

                    console.log('URL API:', url1); // Debugging URL

                    table1.ajax.url(url1).load();
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

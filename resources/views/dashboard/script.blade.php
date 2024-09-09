<script src="{{ asset('dist/js/plugins/chart.js') }}"></script>
<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>

<script>
    // const monthMap = {
    //     'January': '01',
    //     'February': '02',
    //     'March': '03',
    //     'April': '04',
    //     'May': '05',
    //     'June': '06',
    //     'July': '07',
    //     'August': '08',
    //     'September': '09',
    //     'October': '10',
    //     'November': '11',
    //     'December': '12'
    // };

    // Define table1 globally
    let table1;

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
                    data: 'npk',
                    name: 'npk'
                },
                {
                    data: 'nama',
                    name: 'nama'
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
            loadChartData(year);
            table1.ajax.reload();
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
</script>

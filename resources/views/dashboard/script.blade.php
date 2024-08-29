<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

                    var url1 = '{{ route('data.table1') }}' +
                        '?bulan=' + encodeURIComponent(selectedMonth);

                    console.log('Updated URL for table1:', url1);

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
            var bulan = $(this).data('bulan');
            var total = $(this).data('total');
            var tanggal = $(this).data('tanggal');
            var waktu = $(this).data('waktu');

            // Pisahkan tanggal dan waktu untuk ditampilkan dalam format list
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
            $('#detailTanggalWaktu').html(detailHtml); // Masukkan detail tanggal dan waktu
            $('#detailModal').modal('show');
        });
    });
</script>

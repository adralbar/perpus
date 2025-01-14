<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/sweetalert.js') }}"></script>
<script src="{{ asset('dist/js/xlsx.full.min.js') }}"></script> <!-- XLSX -->

<script>
    $(document).ready(function() {
        window.onload = function() {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Silahkan isi Filter tanggal terlebih dahulu!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        };

        var table;

        function loadDataTable() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (startDate && endDate) {
                // Inisialisasi DataTable hanya jika kedua tanggal sudah dipilih
                table = $('#myTable').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: "{{ route('performa.getData') }}",
                        type: 'GET',
                        data: function(d) {
                            d.startDate = startDate;
                            d.endDate = endDate;
                            console.log("Start Date:", startDate);
                            console.log("End Date:", endDate);
                        }
                    },
                    columns: [{
                            data: null,
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
                            data: 'station_id',
                            name: 'station_id'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'shift1',
                            name: 'shift1'
                        },
                        {
                            data: 'waktuci_checkin',
                            name: 'waktuci_checkin'
                        },
                        {
                            data: 'waktu_login_dashboard',
                            name: 'waktu_login_dashboard'
                        },
                        {
                            data: 'selisih_waktu',
                            name: 'selisih_waktu',
                            render: function(data, type, row) {
                                // Misalkan 'data' adalah selisih waktu dalam menit
                                return data + ' menit'; // Tambahkan kata 'menit' setelah nilai
                            }
                        }
                    ],
                    rowCallback: function(row, data, index) {
                        $('td:eq(0)', row).html(index + 1);
                    }
                });
            } else {
                // Jika tanggal belum dipilih, beri peringatan
                alert('Silakan pilih rentang tanggal terlebih dahulu.');
            }
        }

        // Memuat data saat halaman pertama kali dimuat jika tanggal sudah dipilih
        $('#startDate, #endDate').on('change', function() {
            // Memastikan tanggal dipilih terlebih dahulu sebelum menampilkan data
            if ($('#startDate').val() && $('#endDate').val()) {
                // Hancurkan DataTable jika sudah ada, agar bisa diinisialisasi ulang
                if ($.fn.dataTable.isDataTable('#myTable')) {
                    table.clear().destroy();
                }
                loadDataTable(); // Muat DataTable berdasarkan tanggal yang dipilih
            }
        });

        // Menjalankan loadDataTable jika tanggal sudah dipilih saat pertama kali
        if ($('#startDate').val() && $('#endDate').val()) {
            loadDataTable();
        }
    });


    $('#logForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('performa.storeLogs') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
                $('#logModal').modal('hide');
                $('#myTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#userIdForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('performa.storeUserId') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
                $('#userIdModal').modal('hide');
                $('#myTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#exportButton').on('click', function() {
        // Ambil nilai filter bulan dan tahun dari elemen
        var startDate = $('#startDate').val(); // Pastikan ini adalah format yyyy-mm-dd
        var endDate = $('#endDate').val(); // Pastikan ini adalah format yyyy-mm-dd
        var search = $('#dt-search-0').val();
        // Redirect to the export route with query parameters
        window.location.href = "{{ route('performa.export') }}?startDate=" + encodeURIComponent(
                startDate) +
            "&endDate=" + encodeURIComponent(endDate) +
            "&search=" + encodeURIComponent(search);

    });

    // Show loader and disable button broadcast
    document.getElementById('broadcastForm').addEventListener('submit', function(event) {
        document.getElementById('loader').style.display = 'inline-block';
        document.getElementById('broadcastButton').disabled = true;
    });
</script>

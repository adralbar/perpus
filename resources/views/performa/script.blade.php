<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('performa.getData') }}",
                type: 'GET',
                data: function(d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                    console.log("Start Date:", d.startDate);
                    console.log("End Date:", d.endDate);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'npkSistem',
                    name: 'npkSistem'
                },
                {
                    data: 'npk',
                    name: 'npk'
                },
                {
                    data: 'divisi',
                    name: 'divisi'
                },
                {
                    data: 'departement',
                    name: 'departement'
                },
                {
                    data: 'section',
                    name: 'section'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
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
                    name: 'selisih_waktu'
                }
            ]
        });
        $('#startDate, #endDate').on('change', function() {
            table.ajax.reload(); // Reload data berdasarkan rentang tanggal
        });
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
</script>

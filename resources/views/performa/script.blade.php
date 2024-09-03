<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('performa.getData') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
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
</script>

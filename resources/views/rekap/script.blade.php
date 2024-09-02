<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('rekap.getData') }}",
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
                    data: 'waktuci',
                    name: 'waktuci'
                },
                {
                    data: 'waktuco',
                    name: 'waktuco'
                }

            ]
        });


        // Submit data Check-in
        $('#checkinForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('rekap.storeCheckin') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    $('#checkinModal').modal('hide');
                    $('#myTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Submit data Check-out
        $('#checkoutForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('rekap.storeCheckout') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#checkoutModal').modal('hide');
                    $('#myTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

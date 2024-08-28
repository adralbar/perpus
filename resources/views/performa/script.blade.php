<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    });
</script>

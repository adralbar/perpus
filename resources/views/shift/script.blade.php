<script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/query.dataTables.min.js') }}"></script>
<script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>


<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('shift.data') }}',
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
                    data: 'shift1',
                    name: 'shift1'
                },
                {
                    data: 'shift2',
                    name: 'shift2'
                },
                {
                    data: 'shift3',
                    name: 'shift3'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ]
        });
    });
</script>

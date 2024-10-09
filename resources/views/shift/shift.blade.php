    @extends('layout/main')


    <link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css">

    @section('content')
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Data Absensi Karyawan</p>
                <div class="p-3 ml-3 text-black card">
                    <div class="mb-3">
                        <!-- Button to trigger the modal -->
                        <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                            data-bs-target="#shiftModal" onclick="resetForm()">
                            Tambah Shift Karyawan
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#uploadModal">
                            Upload File
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="startDate" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="endDate" class="form-control">
                        </div>
                    </div>
                    <div class="table-wrapper   table-responsive">
                        <table id="myTable" class="table table-dark table-bordered " style="width:100%">
                            <thead id="data-table-head">
                                <tr>
                                </tr>
                            </thead>
                            <tbody id="data-table-body">
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shiftLabel">Tambah/Edit Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="shiftForm">
                            @csrf
                            <input type="hidden" id="shiftId" name="id">

                            <div class="form-group">
                                <label for="npk">NPK Api</label>
                                <div class="form-group">
                                    <select multiple="multiple" size="10" name="npk[]" id="npk"
                                        class="form-control">
                                        @foreach ($userData as $user)
                                            <option value="{{ $user->npk }}">{{ $user->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="shift1">Waktu Shift</label>
                                    <input type="text" class="form-control" id="shift1" name="shift1" required>
                                </div>
                                <div class="form-group">
                                    <label for="start_date">Start date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="end_date">End date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                                <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Upload File -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadLabel">Upload File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="uploadForm" method="POST" enctype="multipart/form-data"
                            action="{{ route('shift.import') }}">
                            @csrf
                            <div class="form-group">
                                <label for="file">Upload File</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal untuk menampilkan detail shift -->
        <div class="modal fade" id="editShiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Shift</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Input untuk shift, tanggal, dan NPK -->
                        <input type="text" id="shift1" placeholder="Shift" />
                        <input type="text" id="date" placeholder="Tanggal" />
                        <input type="text" id="npk" placeholder="NPK" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
        <script src="{{ asset('dist/js/jquery.bootstrap-duallistbox.js') }}"></script>

        </script>

        <script>
            var demo1 = $('select[name="npk[]"]').bootstrapDualListbox();
            $("#demoform").submit(function() {
                alert($('[name="npk[]"]').val());
                return false;
            });
            var demo2 = $('.demo2').bootstrapDualListbox({
                nonSelectedListLabel: 'Non-selected',
                selectedListLabel: 'Selected',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
            });
            $(document).ready(function() {
                var table = $('#myTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('shift.data') }}',
                        data: function(d) {
                            d.startDate = $('#startDate').val(); // Ambil nilai tanggal mulai
                            d.endDate = $('#endDate').val(); // Ambil nilai tanggal selesai
                        }
                    },
                    columns: [{
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'npk',
                            name: 'npk'
                        },
                        {
                            data: 'shift1',
                            name: 'shift1',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'date',
                            name: 'date',
                            visible: false
                        },
                    ],
                    drawCallback: function(settings) {
                        const api = this.api();
                        const data = api.rows({
                            filter: 'applied'
                        }).data().toArray();
                        renderTable(data);
                    },
                });

                $('#startDate, #endDate').on('change', function() {
                    table.ajax.reload(); // Reload data berdasarkan rentang tanggal
                });

                // Fungsi untuk memformat tanggal menjadi 'Kamis, 7 Okt 2024'
                function formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }

                function renderTable(data) {
                    const tableHead = document.getElementById('data-table-head').querySelector('tr');
                    const tableBody = document.getElementById('data-table-body');

                    // Bersihkan header dan body tabel yang ada
                    tableHead.innerHTML = '';
                    tableBody.innerHTML = '';

                    // Buat header untuk Nama (NPK)
                    tableHead.innerHTML = '<th class="sticky-header">Nama (NPK)</th>'; // Header kolom untuk Nama (NPK)

                    // Ambil daftar tanggal dari data
                    const uniqueDates = [...new Set(data.map(entry => entry.date))];

                    uniqueDates.forEach(date => {
                        const th = document.createElement('th');
                        th.textContent = formatDate(
                            date); // Gunakan fungsi formatDate untuk menampilkan tanggal
                        tableHead.appendChild(th);
                    });

                    // Mengelompokkan data berdasarkan nama dan npk
                    const groupedData = {};

                    data.forEach(entry => {
                        const key = `${entry.nama} (${entry.npk})`; // Kunci unik untuk setiap nama dan npk
                        if (!groupedData[key]) {
                            groupedData[key] = {
                                shifts: {},
                                npk: entry.npk
                            };
                        }
                        // Simpan shift berdasarkan tanggal
                        groupedData[key].shifts[entry.date] = entry.shift1 || '';
                    });

                    // Menambahkan data ke dalam tabel
                    for (const [nameNpk, details] of Object.entries(groupedData)) {
                        const row = document.createElement('tr');

                        // Sel untuk Nama (NPK) dengan kelas sticky-header
                        const nameCell = document.createElement('td');
                        nameCell.textContent = nameNpk; // Mengambil nama dan NPK
                        nameCell.classList.add('sticky-header'); // Menambahkan kelas sticky-header
                        row.appendChild(nameCell);

                        uniqueDates.forEach(date => {
                            const shiftCell = document.createElement('td');
                            const shift = details.shifts[date] || ''; // Mengambil shift atau kosong
                            shiftCell.textContent = shift;

                            // Mengatur warna berdasarkan hari
                            const dayOfWeek = new Date(date)
                                .getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                            switch (dayOfWeek) {
                                case 0: // Minggu
                                case 6: // Sabtu
                                    shiftCell.style.backgroundColor =
                                        '#b91c1c'; // Warna gelap untuk sabtu dan minggu
                                    break;
                                case 1: // Senin
                                    shiftCell.style.backgroundColor = '#1e3a8a'; // Biru tua untuk Senin
                                    break;
                                case 2: // Selasa
                                    shiftCell.style.backgroundColor = '#1f2937'; // Hijau tua untuk Selasa
                                    break;
                                case 3: // Rabu
                                    shiftCell.style.backgroundColor = '#ca8a04'; // Kuning keemasan untuk Rabu
                                    break;
                                case 4: // Kamis
                                    shiftCell.style.backgroundColor = '#d946ef'; // Merah muda untuk Kamis
                                    break;
                                case 5: // Jumat
                                    shiftCell.style.backgroundColor = '#9333ea'; // Ungu muda untuk Jumat
                                    break;
                            }

                            shiftCell.addEventListener('click', function() {
                                console.log("Clicked Shift Cell!");
                                console.log("Shift:", shift, "Date:", date, "NPK:", details
                                    .npk); // Debugging

                                // Pastikan nilai yang diambil ada
                                $('#shift1').val(shift ? shift :
                                    ''); // Set input dengan nilai shift yang ada
                                $('#date').val(date); // Set tanggal
                                $('#npk').val(details.npk); // Set NPK

                                // Tampilkan modal
                                $('#editShiftModal').modal('show');
                            });

                            row.appendChild(shiftCell);
                        });

                        tableBody.appendChild(row); // Tambahkan baris ke dalam body tabel
                    }
                }

                $('#shiftForm').submit(function(e) {
                    e.preventDefault(); // Mencegah pengiriman form secara default
                    var id = $('#shiftId').val(); // Ambil nilai ID dari input
                    var url, method;
                    url = '{{ route('shift.store') }}'; // URL untuk menyimpan data baru
                    method = 'POST'; // Metode untuk membuat data baru


                    // AJAX request
                    $.ajax({
                        url: url, // URL yang ditentukan
                        method: method, // Metode yang ditentukan
                        data: $(this)
                            .serialize(), // Mengambil data dari form dan mengubahnya menjadi string
                        success: function(response) {
                            $('#shiftModal').modal('hide'); // Menutup modal setelah sukses
                            table.ajax.reload(); // Memuat ulang data tabel
                            alert(response.success); // Menampilkan pesan sukses
                        },
                        error: function(xhr, status, error) {
                            // Menangani error jika request gagal
                            console.error(xhr.responseText); // Log pesan error ke konsol
                            alert('Terjadi kesalahan: ' + xhr.responseJSON
                                .message); // Tampilkan pesan kesalahan
                        }
                    });

                    // Untuk debugging
                    console.log('URL:', url);
                    console.log('Method:', method);
                    console.log('data:', $(this).serialize());
                });




                // Event listener untuk tombol simpan
                $('#saveShiftBtn').on('click', function() {
                    const shift = $('#shift1').val();
                    const date = $('#date').val();
                    const npk = $('#npk').val();

                    // Kirim data ke server untuk memperbarui shift
                    $.ajax({
                        url: '{{ route('shift.store') }}', // Ganti dengan route yang sesuai
                        type: 'POST',
                        data: {
                            npk: npk,
                            date: date,
                            shift: shift,
                            _token: '{{ csrf_token() }}' // Sertakan CSRF token
                        },
                        success: function(response) {
                            // Tindakan setelah berhasil menyimpan perubahan
                            $('#editShiftModal').modal('hide');
                            $('#myTable').DataTable().ajax
                                .reload(); // Reload data tabel
                            alert('Shift berhasil diperbarui!'); // Pesan sukses
                        },
                        error: function(xhr, status, error) {
                            // Menangani error jika request gagal
                            console.error(xhr
                                .responseText); // Log pesan error ke konsol
                            alert('Terjadi kesalahan: ' + xhr.responseJSON
                                .message); // Tampilkan pesan kesalahan
                        }
                    });
                });



                function deleteShift(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        $.ajax({
                            url: '{{ route('shift.destroy', ':id') }}'.replace(':id', id),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#myTable').DataTable().ajax.reload();

                            }
                        });
                    }
                }
            });

            function resetForm() {
                $('#shiftForm')[0].reset();
                $('#shiftId').val('');
                $('#shiftLabel').text('Tambah Karyawan');
                $('#saveButton').text('Simpan');
            }

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengunggah',
                    html: `
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `, // Tampilkan semua pesan error dalam bentuk list
                });
            @endif
        </script>
    @endsection

    @extends('layout/main')


    <link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css">

    @section('content')
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Shift Karyawan</p>
                <div class="p-3 ml-3 text-black card">
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                            data-bs-target="#shiftModal" onclick="resetForm()">
                            Tambah Shift Karyawan
                        </button>

                        <button type="button" class="btn btn-secondary btn-sm mr-2" data-bs-toggle="modal"
                            data-bs-target="#uploadModal">
                            Upload File
                        </button>

                        <button type="button" class="btn btn-secondary btn-sm mr-2" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            Filter
                        </button>

                        <button type="button" class="btn btn-success btn-sm mr-2" id="exportButton">
                            Download Template
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="exportShift">Export to Excel</button>
                    </div>

                    <div class="table-wrapper   table-responsive">
                        <table id="myTable" class="table table-light table-bordered " style="width:100%">
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
                    </div>
                    <div class="modal-body">
                        <form id="shiftForm">
                            @csrf
                            <input type="hidden" id="shiftId" name="id">
                            <div class="form-group">
                                <label for="npk">NPK Api</label>
                                <select multiple="multiple" size="10" name="npk[]" id="npk"
                                    class="form-control">
                                    @foreach ($userData as $user)
                                        <option value="{{ $user->npk }}">
                                            {{ $user->nama }} ({{ $user->npk }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="shift1">Waktu Shift</label>
                                <select class="form-control" id="shift1" name="shift1" required>
                                    @foreach ($masterShift as $shift)
                                        <option value="{{ $shift }}">{{ $shift }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="shiftLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shiftLabel">Filter Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-6 mb-3 mt-3">
                                    <div class="mb-3">
                                        <label for="departmentFilter"
                                            class="form-label font-weight-bold">Departemen</label>
                                        <select class="form-control" id="departmentFilter" name="department_id"
                                            style="font-weight: 500;">
                                            <option value="">Pilih Departement</option>
                                            <!-- Options will be filled via AJAX -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mt-3">
                                    <label for="sectionFilter" class="form-label font-weight-bold">Section</label>
                                    <select class="form-control" id="sectionFilter" name="section_id"
                                        style="font-weight: 500;">
                                        <option value="">Pilih Section</option>
                                        <!-- Options will be filled via AJAX -->
                                    </select>
                                </div>
                            </div>

                            <label for="npk">NPK Api</label>
                            <select class="dualistbox" multiple="multiple" size="10" name="selected_npk[]"
                                id="selected_npk" class="form-control">
                                @foreach ($userData as $user)
                                    <option value="{{ $user->npk }}">{{ $user->nama }} ({{ $user->npk }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-check form-switch ml-4 mt-1">
                                <input class="form-check-input" type="checkbox" id="toggleStatus"
                                    data-status="{{ request('status', 1) }}"
                                    {{ request('status', 1) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="toggleStatus" id="statusText">
                                    {{ request('status', 1) == 0 ? 'Nonaktif' : 'Aktif' }}
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3 mt-3">
                                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                                    <input type="date" id="startDate" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3 mt-3">
                                    <label for="endDate" class="form-label">Tanggal Selesai</label>
                                    <input type="date" id="endDate" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" id="selectedNPK" name="selectedNPK">
                            <button type="button" class="btn btn-primary" id="filterButton">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

        <div class="modal fade" id="editShiftModal" tabindex="-1" aria-labelledby="editShiftModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShiftModalLabel">Edit Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editShiftForm">
                            @csrf

                            <div class="form-group">
                                <label for="shift1">Waktu Shift</label>
                                <select class="form-control" id="shift1" name="shift1" required>
                                    @foreach ($masterShift as $shift)
                                        <option value="{{ $shift }}">{{ $shift }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date" readonly>
                            </div>
                            <div class="form-group">
                                <label for="npk">NPK</label>
                                <input type="text" class="form-control" id="npk" name="npk" readonly>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-info" id="showHistoryBtn">Riwayat Upload</button>
                                <button type="submit" class="btn btn-primary" id="saveShiftBtn">Edit</button>
                            </div>
                        </form>
                        <div id="shiftHistory" class="mt-3" style="display:none;">
                            <h6>Riwayat Shift pada <span id="historyDate"></span></h6>
                            <table class="table table-light">
                                <thead>
                                    <tr>
                                        <th>NPK</th>
                                        <th>Shift</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody id="historyBody">
                                </tbody>
                            </table>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info" id="showEditShift">Edit shift</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
        {{-- <script src="{{ asset('dist/js/jquery.bootstrap-duallistbox.js') }}"></script> --}}
        <script src="{{ asset('dist/js/exceljs.min.js') }}"></script>
        <script src="{{ asset('lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>


        @if (Auth::user()->role_id != 1)
            <script>
                $(document).ready(function() {
                    function getToday() {
                        const today = new Date();
                        today.setDate(today.getDate() + 1); // Tambahkan 1 hari
                        return today.toISOString().split('T')[0];
                    }

                    // Fungsi untuk mendapatkan tanggal maksimal (14 hari ke depan)
                    function getMaxDate() {
                        const today = new Date();
                        const dayOfWeek = today.getDay(); // 0 (Minggu) - 6 (Sabtu)
                        const daysUntilEndOfWeek = 7 - dayOfWeek; // Hari tersisa hingga akhir minggu ini
                        today.setDate(today.getDate() + daysUntilEndOfWeek + 14); // Akhir minggu ini + 14 hari
                        return today.toISOString().split('T')[0];
                    }

                    // Set atribut min dan max
                    $('#start_date').attr({
                        min: getToday(),
                        max: getMaxDate()
                    });
                    $('#end_date').attr({
                        min: getToday(),
                        max: getMaxDate()
                    });
                });
            </script>
        @endif
        <script>
            document.getElementById('exportButton').addEventListener('click', function() {
                // Ambil data pengguna yang diteruskan dari Laravel
                var userData = @json($userData);

                // Persiapkan data untuk file Excel
                var workbook = new ExcelJS.Workbook();
                var worksheet = workbook.addWorksheet('Template');

                // Definisikan heading untuk kolom
                var headings = ['NPK', 'Nama', 'Jadwal Shift', 'Start Date', 'End Date', '', '',
                    'Contoh Shift yang terdaftar', 'Format Tanggal (startdate & enddate)'
                ];

                // Tambahkan header ke worksheet
                var headerRow = worksheet.addRow(headings);

                headerRow.eachCell(function(cell, colNumber) {
                    cell.style.numFmt = '@'; // Pastikan format teks untuk semua kolom

                    if (colNumber !== 6 && colNumber !== 7) { // Kolom F dan G tidak distyling
                        cell.style.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: {
                                argb: 'FFFF00'
                            } // Warna kuning
                        };
                        cell.style.font = {
                            bold: true // Teks menjadi bold
                        };
                    }
                });

                // Menambahkan data pengguna
                userData.forEach(function(user) {
                    worksheet.addRow([
                        user.npk.toString(), // Pastikan NPK dianggap sebagai teks
                        user.nama.toString(), // Pastikan Nama dianggap sebagai teks
                        '', '', '', '', '', '', ''
                    ]);
                });

                // Atur lebar kolom
                worksheet.columns = [{
                        width: 15
                    }, // NPK
                    {
                        width: 25
                    }, // Nama
                    {
                        width: 20
                    }, // Jadwal Shift
                    {
                        width: 15
                    }, // Start Date
                    {
                        width: 15
                    }, // End Date
                    {
                        width: 10
                    }, // Kolom kosong F
                    {
                        width: 10
                    }, // Kolom kosong G
                    {
                        width: 30
                    }, // Contoh Shift
                    {
                        width: 20
                    } // Format Tanggal
                ];

                worksheet.eachRow({
                    includeEmpty: true
                }, function(row, rowNumber) {
                    row.eachCell({
                        includeEmpty: true
                    }, function(cell, colNumber) {
                        {
                            cell.style.numFmt = '@';
                        }
                    });
                });

                // Menambahkan contoh jadwal shift (langsung setelah header)
                const shiftSchedules = [
                    '06:00 - 15:00', '07:00 - 16:00', '14:00 - 23:00', '13:00 - 22:00',
                    '21:00 - 06:00', '22:00 - 07:00', '23:00 - 08:00', '06:00 - 15:20',
                    '07:00 - 16:30', '15:00 - 00:00', '16:00 - 01:00', '08:00 - 17:20',
                    '09:00 - 18:20', '08:00 - 17:00', 'Dinas Luar Stand By'
                ];

                let rowIndex = 2; // Mulai dari baris 2, setelah header
                shiftSchedules.forEach(function(schedule, index) {
                    // Menulis data shift schedule ke kolom H
                    let cell = worksheet.getCell(`H${rowIndex + index}`);
                    cell.value = schedule;
                    cell.style.numFmt = '@'; // Pastikan format teks
                });

                // Menambahkan catatan (Notes)
                worksheet.getCell(`I2`).value = 'YYYY-MM-DD contoh: ';
                worksheet.getCell(`I2`).style.numFmt = '@'; // Format teks

                worksheet.getCell(`I3`).value = '2024-11-29';
                worksheet.getCell(`I3`).style.numFmt = '@'; // Format teks
                const noteRowIndex = rowIndex + shiftSchedules.length + 1;

                worksheet.getCell(`H${noteRowIndex - 1}`).value = 'NB : Pastikan number format text';
                worksheet.getCell(`H${noteRowIndex - 1}`).style.numFmt = '@'; // Format teks
                worksheet.getCell(`H${noteRowIndex - 1}`).style.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: 'FF0000'
                    } // Background merah
                };
                worksheet.getCell(`H${noteRowIndex - 1}`).style.font = {
                    bold: true // Teks menjadi bold
                };

                // Ekspor workbook sebagai file Excel
                workbook.xlsx.writeBuffer().then(function(buffer) {
                    var blob = new Blob([buffer], {
                        type: 'application/octet-stream'
                    });
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'TEMPLATE SHIFT.xlsx';
                    link.click();
                });
            });


            let shiftHistoryUrl;
            var $dualistbox = $('select[name="selected_npk[]"]').bootstrapDualListbox({
                selectorMinimalHeight: 200,
                nonSelectedListLabel: 'NPK Tersedia',
                selectedListLabel: 'NPK Terpilih',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                nonSelectedFilter: '',

            });

            function updateDualListbox() {
                if (!departmentId) return; // Jika departmentId tidak ada

                var fetchUrl = "{{ route('get.karyawan') }}";

                // AJAX untuk mendapatkan data karyawan berdasarkan departemen dan status
                $.ajax({
                    url: fetchUrl,
                    method: 'GET',
                    data: {
                        status: status,
                        department_id: departmentId,
                        section_id: sectionId,
                    },
                    success: function(data) {
                        // Kosongkan opsi yang ada di dual listbox
                        $dualistbox.empty();

                        // Tambahkan opsi baru dari data
                        data.userData.forEach(function(user) {
                            $('<option>', {
                                value: user.npk,
                                text: `${user.nama} (${user.npk})`,
                            }).appendTo($dualistbox);
                        });

                        // Refresh tampilan dual listbox
                        $dualistbox.bootstrapDualListbox('refresh');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            var demo1 = $('select[name="npk[]"]').bootstrapDualListbox({
                selectorMinimalHeight: 200,
                nonSelectedListLabel: 'NPK Tersedia',
                selectedListLabel: 'NPK Terpilih',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                nonSelectedFilter: '',

            });
            var demo2 = $('.demo2').bootstrapDualListbox({
                selectorMinimalHeight: 200,
                nonSelectedListLabel: 'Non-selected',
                selectedListLabel: 'Selected',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                nonSelectedFilter: 'ion ([7-9]|[1][0-2])'

            });

            function updateDualListbox() {
                // Pastikan departmentId dan sectionId tersedia

                var fetchUrl = "{{ route('get.karyawan') }}";

                // AJAX untuk mendapatkan data karyawan berdasarkan departemen, seksi, dan status
                $.ajax({
                    url: fetchUrl,
                    method: 'GET',
                    data: {
                        status: status,
                        department_id: departmentId,
                        section_id: sectionId,
                    },
                    success: function(data) {
                        // Kosongkan opsi yang ada di dual listbox
                        $dualistbox.empty();

                        // Tambahkan opsi baru dari data
                        data.userData.forEach(function(user) {
                            $('<option>', {
                                value: user.npk,
                                text: `${user.nama} (${user.npk})`,
                            }).appendTo($dualistbox);
                        });

                        // Refresh tampilan dual listbox
                        $dualistbox.bootstrapDualListbox('refresh');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            $(document).on('click', '#toggleStatus', function() {
                status = this.checked ? 0 : 1; // Update status saat toggle berubah
                updateDualListbox(); // Memperbarui dual listbox
            });

            // Event untuk perubahan pada departmentFilter
            $(document).on('change', '#departmentFilter', function() {
                departmentId = $(this).val(); // Update departmentId saat departemen berubah
                updateDualListbox(); // Memperbarui dual listbox
            });

            // Event untuk perubahan pada sectionFilter
            $(document).on('change', '#sectionFilter', function() {
                sectionId = $(this).val(); // Update sectionId saat seksi berubah
                updateDualListbox(); // Memperbarui dual listbox
            });


            $(document).ready(function() {
                $(document).ready(function() {
                    function loadDepartments() {
                        $.ajax({
                            url: '{{ route('get.department') }}', // Tetap menggunakan route yang sudah ada
                            method: 'GET',
                            success: function(response) {
                                const departmentSelect = $('#departmentFilter');
                                departmentSelect.empty(); // Kosongkan dropdown
                                departmentSelect.append(
                                    '<option value="">Pilih Departemen</option>'); // Opsi default

                                // Isi dropdown dengan departemen yang diterima dari API
                                response.departments.forEach(function(department) {
                                    departmentSelect.append(`
                        <option value="${department.id}">${department.nama}</option>
                    `);
                                });
                            },
                            error: function() {
                                alert('Gagal memuat data departemen');
                            }
                        });
                    }

                    function loadSections() {
                        $.ajax({
                            url: '{{ route('get.department') }}', // Tetap menggunakan route yang sudah ada
                            method: 'GET',
                            success: function(response) {
                                const sectionSelect = $('#sectionFilter');
                                sectionSelect.empty(); // Kosongkan dropdown
                                sectionSelect.append(
                                    '<option value="">Pilih Section</option>'); // Opsi default

                                // Isi dropdown dengan seksi yang diterima dari API
                                response.sections.forEach(function(section) {
                                    sectionSelect.append(`
                        <option value="${section.id}">${section.nama}</option>
                    `);
                                });
                            },
                            error: function() {
                                alert('Gagal memuat data seksi');
                            }
                        });
                    }

                    // Panggil fungsi untuk memuat data awal
                    loadDepartments();
                    loadSections();
                });
                var table = $('#myTable').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ajax: {
                        url: '{{ route('shift.data') }}',
                        data: function(d) {
                            d.startDate = $('#startDate').val();
                            d.endDate = $('#endDate').val();
                            d.selected_npk = $('#selectedNPK')
                                .val();
                        }
                    },
                    pageLength: -1,
                    deferLoading: 0,
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
                        }
                    ],
                    drawCallback: function(settings) {
                        const api = this.api();
                        const data = api.rows({
                            filter: 'applied'
                        }).data().toArray();
                        renderTable(data);
                    },
                });

                // Modal shown event
                $('#filterModal').on('shown.bs.modal', function() {
                    $('.duallistbox').bootstrapDualListbox({
                        nonSelectedListLabel: 'Available Members',
                        selectedListLabel: 'Selected Members',
                        preserveSelectionOnMove: 'moved',
                        moveOnSelect: false,
                        filterPlaceHolder: 'Filter',
                        moveAllLabel: 'Move all',
                        removeAllLabel: 'Remove all',
                        infoTextEmpty: 'No Members available',
                        infoText: 'Showing {0} members',
                        infoTextFiltered: '<span class="badge badge-warning">Filtered</span> {0} from {1}',
                        infoTextSelected: '{0} members selected'
                    });
                });

                // $('#exportButton').on('click', function() {
                //     var startDate = $('#startDate').val();
                //     var endDate = $('#endDate').val();
                //     var search = $('#dt-search-0').val();
                //     window.location.href = "{{ route('exportTemplate') }}?startDate=" + encodeURIComponent(
                //             startDate) +
                //         "&endDate=" + encodeURIComponent(endDate) +
                //         "&search=" + encodeURIComponent(search);

                // });

                $('#filterButton').click(function(e) {
                    e.preventDefault();
                    var selectedNPK = $('#selected_npk').val() || []; // Ambil nilai NPK yang dipilih
                    console.log('NPK yang dipilih: ', selectedNPK);

                    if (selectedNPK.length > 0) {
                        console.log('Menutup modal dan menyimpan NPK: ', selectedNPK.join(','));
                        $('#filterModal').modal('hide');
                        $('#selectedNPK').val(selectedNPK.join(',')); // Simpan NPK yang dipilih
                        table.ajax.reload();
                    } else {
                        alert('Silakan pilih karyawan terlebih dahulu.');
                        console.log('Tidak ada karyawan yang dipilih.');
                    }
                });

                $('#startDate, #endDate').on('change', function() {
                    table.ajax.reload();
                });



                // Format date
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

                    tableHead.innerHTML = '';
                    tableBody.innerHTML = '';

                    tableHead.innerHTML = `<th class="sticky-header">Nama (NPK)</th>`;

                    const uniqueDates = [...new Set(data.map(entry => entry.date))];

                    uniqueDates.forEach(date => {
                        const th = document.createElement('th');
                        th.textContent = formatDate(date);
                        tableHead.appendChild(th);
                    });

                    const groupedData = {};
                    data.forEach(entry => {
                        const key = `${entry.nama} (${entry.npk})`;
                        if (!groupedData[key]) {
                            groupedData[key] = {
                                shifts: {},
                                npk: entry.npk
                            };
                        }
                        groupedData[key].shifts[entry.date] = entry.shift1 || '';
                    });

                    for (const [nameNpk, details] of Object.entries(groupedData)) {
                        const row = document.createElement('tr');
                        const nameCell = document.createElement('td');
                        nameCell.textContent = nameNpk;
                        nameCell.classList.add('sticky-header');
                        row.appendChild(nameCell);

                        uniqueDates.forEach(date => {
                            const shiftCell = document.createElement('td');
                            const shift = details.shifts[date] || '';
                            shiftCell.textContent = shift;

                            const dayOfWeek = new Date(date).getDay();
                            switch (dayOfWeek) {
                                case 0:
                                case 6:
                                    shiftCell.style.backgroundColor = '#f87171';
                                    break;
                                case 1: // Senin
                                    shiftCell.style.backgroundColor = '#60a5fa';
                                    break;
                                case 2: // Selasa
                                    shiftCell.style.backgroundColor = '#86efac';
                                    break;
                                case 3: // Rabu
                                    shiftCell.style.backgroundColor = '#facc15';
                                    break;
                                case 4: // Kamis
                                    shiftCell.style.backgroundColor = '#f9a8d4';
                                    break;
                                case 5: // Jumat
                                    shiftCell.style.backgroundColor = '#c4b5fd';
                                    break;
                            }


                            // Click event for shift cell
                            shiftCell.addEventListener('click', function() {
                                console.log("Clicked Shift Cell!");
                                console.log("Shift:", shift, "Date:", date, "NPK:", details
                                    .npk);
                                $('#editShiftModal').modal('show');
                                $('#editShiftModal #shift1').val(shift);
                                $('#editShiftModal #date').val(date);
                                $('#editShiftModal #npk').val(details.npk);

                                // Create URL for shift history
                                shiftHistoryUrl =
                                    "{{ route('shift.history', ['npk' => 'npkPlaceholder', 'date' => 'datePlaceholder']) }}"
                                    .replace('npkPlaceholder', details.npk)
                                    .replace('datePlaceholder', date)
                                    .replace(/&amp;/g, '&');
                                console.log(shiftHistoryUrl);

                                $.ajax({
                                    url: shiftHistoryUrl,
                                    method: 'GET',
                                    data: {
                                        npk: details.npk,
                                        date: date
                                    },
                                    success: function(response) {
                                        $('#historyBody').empty();
                                        if (response.data.length > 0) {
                                            response.data.forEach(function(
                                                shift) {
                                                $('#historyBody')
                                                    .append(`
                                        <tr>
                                            <td>${shift.npk}</td>
                                            <td>${shift.shift1}</td>
                                            <td>${shift.date}</td>
                                        </tr>
                                    `);
                                            });
                                        } else {
                                            $('#historyBody').append(
                                                '<tr><td colspan="3">Tidak ada data shift</td></tr>'
                                            );
                                        }
                                    },
                                    error: function() {
                                        alert(
                                            'Gagal mengambil data riwayat shift.'
                                        );
                                    }
                                });
                            });

                            row.appendChild(shiftCell); // Add shift cell to the row
                        });

                        tableBody.appendChild(row); // Add row to table body
                    }
                }

                // Show history button click event
                $('#showHistoryBtn').on('click', function() {
                    const npk = $('#npk').val();
                    const date = $('#date').val();

                    if (!npk || !date) {
                        alert('NPK dan Tanggal harus diisi!');
                        return;
                    }
                    $('#editShiftForm').hide();
                    $('#shiftHistory').show();

                    // Fetch shift history
                    $.ajax({
                        url: shiftHistoryUrl,
                        method: 'GET',
                        data: {
                            npk: npk,
                            date: date
                        },
                        success: function(response) {
                            $('#historyBody').empty();
                            if (response.data.length > 0) {
                                response.data.forEach(function(shift) {
                                    $('#historyBody').append(`
                                <tr>
                                    <td>${shift.npk}</td>
                                    <td>${shift.shift1}</td>
                                    <td>${shift.date}</td>
                                </tr>
                            `);
                                });
                            } else {
                                $('#historyBody').append(
                                    '<tr><td colspan="3">Tidak ada data shift</td></tr>'
                                );
                            }
                        },
                        error: function() {
                            alert('Gagal mengambil data riwayat shift.');
                        }
                    });
                });


                $('#shiftForm').submit(function(e) {
                    e.preventDefault(); // Mencegah pengiriman form secara default
                    var id = $('#shiftId').val(); // Ambil nilai ID dari input
                    var url, method;
                    url = '{{ route('shift.store') }}'; // URL untuk menyimpan data baru
                    method = 'POST'; // Metode untuk membuat data baru
                    $.ajax({
                        url: url,
                        method: method,
                        data: $(this)
                            .serialize(),
                        success: function(response) {
                            $('#shiftModal').modal('hide');
                            // table.ajax.reload();
                            alert(response.success);
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 403) {
                                // Menangkap pesan error khusus untuk status 403
                                alert('Terjadi kesalahan: ' + xhr.responseJSON.error);
                            } else {
                                console.error('Error:', xhr.responseText);
                                alert('Terjadi kesalahan: ' + xhr.responseJSON
                                    .message ||
                                    'Silakan coba lagi.');
                            }
                        }
                    });


                    console.log('URL:', url);
                    console.log('Method:', method);
                    console.log('data:', $(this).serialize());
                });
                $('#editShiftForm').submit(function(e) {
                    e.preventDefault();

                    const shift1 = $('#shift1').val();
                    const date = $('#date').val();
                    const npk = $('#npk').val();

                    $.ajax({
                        url: '{{ route('shift.store2') }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#editShiftModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                            alert('Shift berhasil diperbarui!');
                        },

                        error: function(xhr, status, error) {
                            if (xhr.status === 403) {
                                // Menangkap pesan error khusus untuk status 403
                                alert('Terjadi kesalahan: ' + xhr.responseJSON.error);
                            } else {
                                console.error('Error:', xhr.responseText);
                                alert('Terjadi kesalahan: ' + xhr.responseJSON
                                    .message ||
                                    'Silakan coba lagi.');
                            }
                        }
                    });
                });

                $('#showEditShift').on('click', function() {
                    $('#shiftHistory').hide();
                    $('#editShiftForm').show();

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


            $('#exportShift').on('click', async function() {
                console.log('Button clicked');

                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();
                let selectedNPK = $('#selectedNPK').val();

                if (!startDate || !endDate || !selectedNPK) {
                    alert('Harap isi semua filter sebelum ekspor!');
                    return;
                }

                try {
                    console.log('Fetching data...');
                    const response = await fetch(
                        `{{ route('exportData') }}?startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}&selected_npk=${encodeURIComponent(selectedNPK)}`
                    );
                    const data = await response.json();

                    console.log('Response received:', data);

                    if (response.ok && data.length > 0) {
                        console.log('Creating Excel file...');
                        const workbook = new ExcelJS.Workbook();
                        const worksheet = workbook.addWorksheet('Shift Data');

                        // Set header with styling
                        worksheet.columns = [{
                                header: 'NPK',
                                key: 'npk',
                                width: 15
                            },
                            {
                                header: 'Nama',
                                key: 'nama',
                                width: 25
                            },
                            {
                                header: 'Tanggal',
                                key: 'tanggal',
                                width: 15
                            },
                            {
                                header: 'Shift',
                                key: 'shift',
                                width: 10
                            },
                            {
                                header: 'Section',
                                key: 'section_nama',
                                width: 10
                            },
                            {
                                header: 'Department',
                                key: 'department_nama',
                                width: 10
                            },
                            {
                                header: 'Division',
                                key: 'division_nama',
                                width: 10
                            },
                        ];

                        worksheet.getRow(1).eachCell((cell) => {
                            cell.font = {
                                bold: true
                            };
                            cell.fill = {
                                type: 'pattern',
                                pattern: 'solid',
                                fgColor: {
                                    argb: 'FFFF00'
                                }, // Kuning
                            };
                            cell.alignment = {
                                horizontal: 'left',
                                vertical: 'middle'
                            };
                        });

                        // Add data rows
                        worksheet.addRows(data);

                        console.log('Excel file created. Downloading...');
                        const buffer = await workbook.xlsx.writeBuffer();

                        const blob = new Blob([buffer], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = 'DATASHIFT.xlsx';
                        link.click();
                        console.log('Download completed.');
                    } else {
                        console.warn('No data found:', data.message);
                        alert(data.message || 'Data tidak ditemukan!');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat ekspor data!');
                }
            });
        </script>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `{!! session('error') !!}`
                });
            </script>
        @endif

        <style>
            #dt-length-0 {
                display: none;
            }

            label[for="dt-length-0"] {
                display: none;
            }
        </style>
    @endsection

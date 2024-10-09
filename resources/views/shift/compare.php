<?php 
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
                        const key = ${entry.nama} (${entry.npk}); // Kunci unik untuk setiap nama dan npk
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
                                if (shift) {
                                    $('#shift1').val(shift); // Set input dengan nilai shift yang ada
                                } else {
                                    $('#shift1').val(''); // Atur menjadi kosong jika tidak ada
                                }

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
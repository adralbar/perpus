@extends('layout/main')

<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Katalog Perpustakaan</p>
                <div class="p-3 ml-3 text-black card">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            @if (Auth::user()->role_id == 1)
                                <button type="button" class="btn btn-sm mr-2"
                                    style="background-color: #4b0082; color: white;" data-bs-toggle="modal"
                                    data-bs-target="#tambahBuku">
                                    Tambah Buku
                                </button>
                            @endif
                        </div>
                        <div class="form-group ms-auto">
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="container mt-5">
                        <div class="row" id="cardContainer">
                            <!-- Data buku akan dimuat di sini menggunakan AJAX -->
                        </div>
                    </div>
                    <div class="pagination-controls"
                        style="margin-bottom: 20px; display: flex; justify-content: space-between;">
                        <button id="prevPage" class="btn btn-primary" style="display:none;">Prev</button>
                        <button id="nextPage" class="btn btn-primary" style="display:none;">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambahBuku" tabindex="-1" aria-labelledby="tambahBukuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahBukuLabel">Tambah Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahBukuForm">
                        @csrf
                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis Buku</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" required>
                        </div>
                        <div class="form-group">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Terbit</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="nomorisbn">Nomor ISBN</label>
                            <input type="text" class="form-control" id="nomorisbn" name="nomorisbn" required>
                        </div>
                        <div class="form-group">
                            <label for="bahasa">Bahasa</label>
                            <input type="text" class="form-control" id="bahasa" name="bahasa" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ringkasan">Ringkasan</label>
                            <input type="text" class="form-control" id="ringkasan" name="ringkasan" required>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .card-photo {
            width: 100%;
            height: 200px;
            /* Sesuaikan tinggi div sesuai kebutuhan */
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            /* Tambahkan padding untuk memberi jarak antara gambar dan tepi card */
        }

        .photo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            /* Gambar tetap proporsional dan mengisi div */
        }
    </style>

    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/sweetalert.js') }}"></script>
    <script>
        $(document).ready(function() {
            var currentPage = 1;

            function loadBuku(page) {
                var kategori = $('#kategori').val(); // Ambil kategori yang dipilih

                $.ajax({
                    url: '{{ route('katalog.getBuku') }}',
                    method: 'GET',
                    data: {
                        page: page,
                        kategori: kategori // Kirim kategori sebagai parameter
                    },
                    success: function(data) {
                        // Clear previous cards
                        $('#cardContainer').html('');

                        // Loop through the data and create cards
                        data.data.forEach(function(buku) {
                            var cardHTML = `
                    <div class="col-md-4 mb-4">
                        <div class="card" data-id="${buku.id}" style="cursor: pointer;">
                            <div class="card-photo">
                                <img src="${buku.foto}" class="photo-img" alt="${buku.judul}">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title font-weight-bold">${buku.judul}</h3>
                                <p class="card-text">${buku.ringkasan.length > 100 ? buku.ringkasan.substring(0, 100) + '...' : buku.ringkasan}</p>
                            </div>
                        </div>
                    </div>
                `;
                            $('#cardContainer').append(cardHTML);
                        });

                        // Handle pagination buttons
                        $('#nextPage').toggle(data.current_page < data.total_pages);
                        $('#prevPage').toggle(data.current_page > 1);

                        // Add click event to each card
                        $('.card').click(function() {
                            var bukuId = $(this).data('id');
                            if (bukuId) {
                                window.location.href = '/buku/detail/' + bukuId;
                            } else {
                                console.log("ID Buku tidak ditemukan!");
                            }
                        });
                    },
                    error: function(error) {
                        console.log('Error fetching data:', error);
                    }
                });
            }

            // Load initial books
            var currentPage = 1;
            loadBuku(currentPage);

            // Event listener untuk kategori
            $('#kategori').change(function() {
                console.log("Kategori terpilih:", $(this).val());
                currentPage = 1; // Reset ke halaman pertama saat kategori berubah
                loadBuku(currentPage);
            });

            // Next & Previous Page Buttons
            $('#nextPage').click(function() {
                currentPage++;
                loadBuku(currentPage);
            });

            $('#prevPage').click(function() {
                currentPage--;
                loadBuku(currentPage);
            });

        });

        $('#tambahBukuForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this); // Menggunakan FormData untuk menyertakan file

            $.ajax({
                url: "{{ route('storeDaftarBuku') }}",
                method: "POST",
                data: formData,
                processData: false, // Jangan proses data, biarkan FormData yang menangani
                contentType: false, // Jangan set tipe konten secara otomatis
                success: function(response) {
                    console.log("Success response: ", response);
                    $('#tambahBuku').modal('hide');
                    table.ajax.reload();
                },
                error: function(xhr) {
                    console.error("Error response: ", xhr.responseText);
                }
            });
        });
    </script>
@endsection

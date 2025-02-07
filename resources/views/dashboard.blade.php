@extends('layout/main')

<link rel="stylesheet" href="{{ asset('dist/css/plugins/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/plugins/bootstrap.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-duallistbox.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="p-3">
                <p class="pl-3 pb-3 font-weight-bold h3">Buku Terbaru</p>
                <div class="p-3 ml-3 text-black card">

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

    <style>
        .card-photo {
            width: 80%;
            height: 200px;
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;

        }

        .photo-img {
            max-width: 100%;
            max-height: 100%;
            margin-bottom: 0;
            padding-bottom: 0;
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
                $.ajax({
                    url: '{{ route('katalog.getBuku-dashboard') }}',
                    method: 'GET',
                    data: {
                        page: page
                    }, // Mengirimkan nomor halaman
                    success: function(data) {
                        // Clear previous cards
                        $('#cardContainer').html('');

                        // Loop through the data and create cards
                        data.data.forEach(function(buku) {
                            var cardHTML = `
                                <div class="col-md-12 mb-4">
                                    <div class="card" data-id="${buku.id}" style="cursor: pointer;">
                                        <div class="row no-gutters">
                                            <div class="col-md-3">
                                                <div class="card-photo">
                                                    <img src="${buku.foto}" class="photo-img" alt="${buku.judul}">
                                                </div>
                                            </div>
                                            <!-- Teks di kanan -->
                                            <div class="col-md-9">
                                                <div class="card-body">
                                                    <h3 class="card-title font-weight-bold">${buku.judul}</h3>
                                                    <p class="card-text">${buku.ringkasan.length > 100 ? buku.ringkasan.substring(0, 200) + '...' : buku.ringkasan}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('#cardContainer').append(cardHTML);
                        });

                        // Handle pagination buttons
                        if (data.current_page < data.total_pages) {
                            $('#nextPage').show();
                        } else {
                            $('#nextPage').hide();
                        }

                        if (data.current_page > 1) {
                            $('#prevPage').show();
                        } else {
                            $('#prevPage').hide();
                        }

                        // Add click event to each card
                        $('.card').click(function() {
                            var bukuId = $(this).data('id'); // Ambil ID dari data-id
                            if (bukuId) { // Pastikan ID valid
                                window.location.href = '/buku/detail/' +
                                    bukuId; // Arahkan ke halaman detail buku
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

            // Load initial page
            loadBuku(currentPage);

            // Next Page Button
            $('#nextPage').click(function() {
                currentPage++;
                loadBuku(currentPage);
            });

            // Previous Page Button
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

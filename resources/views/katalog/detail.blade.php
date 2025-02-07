@extends('layout/main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4"> <!-- Mengubah lebar kolom card -->
                <div class="card">
                    <img src="{{ $buku->foto }}" class="card-img-top img-thumbnail" alt="{{ $buku->judul }}"
                        style="max-width: 100%; max-height: 300px; object-fit: contain;">
                </div>
            </div>
            <div class="col-md-8">
                <h2>{{ $buku->judul }}</h2>
                <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                <p><strong>kategori:</strong> {{ $buku->kategori }}</p>
                <p><strong>Deskripsi:</strong> {{ $buku->ringkasan }}</p>
                <p><strong>Tahun Terbit:</strong> {{ $buku->tanggal }}</p>

                <!-- Tombol Edit dan Hapus -->

                @if (Auth::user()->role_id == 1)
                    <button type="button" class="btn btn-primary btn-sm mr-2" data-bs-toggle="modal"
                        data-bs-target="#editBukuModal" id="editBukuBtn">
                        Edit Buku
                    </button>

                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</button>
                    </form>
                @endif

                @if (Auth::user()->role_id == 2)
                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-5" id="pinjamBuku"
                        data-id="{{ $buku->id }}">
                        Pinjam Buku
                    </button>

                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-5" id="readlist"
                        data-id="{{ $buku->id }}">
                        Tambahkan ke readlist
                    </button>
                @endif

            </div>
        </div>
    </div>
    <!-- Modal Edit Buku -->
    <div class="modal fade" id="editBukuModal" tabindex="-1" role="dialog" aria-labelledby="editBukuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBukuModalLabel">Edit Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editBukuForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <!-- ID Buku (Hidden) -->
                        <input type="hidden" name="id" id="bukuId">
                        <!-- Form fields -->
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" required>
                        </div>
                        <div class="form-group">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tahun Terbit</label>
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
                            <input type="text" class="form-control" id="kategori" name="kategori" required>
                        </div>
                        <div class="form-group">
                            <label for="ringkasan">Ringkasan</label>
                            <textarea class="form-control" id="ringkasan" name="ringkasan" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                            <img id="fotoPreview" src="" alt="Foto Buku" class="img-thumbnail"
                                style="margin-top: 10px; display:none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <style>
        .card-img-top {

            overflow: hidden;
            background-color: #f8f9fa;

            padding: 10px;
            /* Tambahkan padding untuk memberi jarak antara gambar dan tepi card */
        }
    </style>
    <script src="{{ asset('dist/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('dist/js/plugins/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $(document).on('click', '#readlist', function() {
                var bukuId = $(this).data('id');
                console.log(bukuId); // Akan menampilkan id buku yang dipilih di console
            });

            $('#readlist').click(function() {
                var bukuId = $(this).data('id');

                // Cek apakah buku sudah ada di readlist
                $.ajax({
                    url: '/check-readlist', // Rute untuk memeriksa apakah buku ada di readlist
                    type: 'GET',
                    data: {
                        buku_id: bukuId
                    },
                    success: function(response) {
                        if (response.exists) {
                            // Jika buku sudah ada di readlist, disable tombol
                            $('#readlist').prop('disabled', true);
                            alert('Buku sudah ada di readlist!');
                        } else {
                            // Jika belum ada, lanjutkan dengan menambahkan ke readlist
                            $.ajax({
                                url: '/add-to-readlist',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    buku_id: bukuId
                                },
                                success: function(response) {
                                    alert('Buku berhasil ditambahkan ke readlist!');
                                    $('#readlist').prop('disabled',
                                        true
                                    ); // Disable tombol setelah berhasil ditambahkan
                                },
                                error: function(xhr, status, error) {
                                    alert('Terjadi kesalahan, coba lagi.');
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan, coba lagi.');
                    }
                });
            });

            $('#pinjamBuku').click(function() {
                var bukuId = $(this).data('id');
                $.ajax({
                    url: '/add-to-pinjam', // Ganti dengan rute yang sesuai
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        buku_id: bukuId
                    },
                    success: function(response) {
                        // Tangani respon sukses
                        alert('Status buku kini telah berhasil diperbarui menjadi dipinjam');
                    },
                    error: function(xhr, status, error) {
                        // Tangani error jika ada
                        alert('Terjadi kesalahan, coba lagi.');
                    }
                });
            });
            // Fungsi untuk mengambil ID setelah '/detail/' pada URL
            function getIdFromUrl() {
                var url = window.location.pathname; // Ambil path URL
                var parts = url.split('/'); // Pisahkan berdasarkan '/'
                return parts[parts.length - 1]; // Ambil bagian terakhir
            }

            // Ambil ID dari URL
            var bukuId = getIdFromUrl();

            // Debugging untuk memastikan ID buku terambil dengan benar
            console.log("ID Buku: " + bukuId);

            // Set data-id tombol dengan ID yang diambil dari URL
            if (bukuId) {
                $('#editBukuBtn').attr('data-id', bukuId);
            }

            // Fungsi untuk mengisi data ke form edit modal
            $('#editBukuBtn').click(function() {
                var id = $(this).data('id'); // Mendapatkan ID dari data-id pada tombol

                $.ajax({
                    url: '/buku/detail2/' + id, // Gunakan ID untuk request data
                    method: 'GET',
                    success: function(response) {
                        // Cek response di console
                        console.log(response);

                        if (response && response.id) {
                            // Isi form dengan data yang didapat
                            $('#bukuId').val(response.id);
                            $('#judul').val(response.judul);
                            $('#penulis').val(response.penulis);
                            $('#penerbit').val(response.penerbit);
                            $('#tanggal').val(response.tanggal);
                            $('#nomorisbn').val(response.nomorisbn);
                            $('#bahasa').val(response.bahasa);
                            $('#kategori').val(response.kategori);
                            $('#ringkasan').val(response.ringkasan);
                            // Menampilkan modal
                            $('#editBukuModal').modal('show');
                        } else {
                            alert('Data buku tidak ditemukan!');
                        }
                    },
                    error: function() {
                        alert('Gagal mendapatkan data buku!');
                    }
                });
            });

            // Form submission untuk update buku
            $('#editBukuForm').submit(function(e) {
                e.preventDefault();

                let id = $('#bukuId').val(); // Ambil ID dari input hidden yang ada di form

                if (!id) {
                    alert("Terjadi kesalahan: ID buku tidak ditemukan!");
                    return;
                }

                console.log("ID Buku:", id); // Debugging

                let formData = new FormData(this);
                formData.append('_method', 'PUT'); // Laravel butuh ini untuk metode PUT

                $.ajax({
                    url: "{{ url('buku/update') }}/" + id, // Gunakan ID yang diambil dari form
                    method: "POST", // Gunakan POST meskipun kita ingin menggunakan PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Pastikan CSRF token ada di Blade
                    },
                    beforeSend: function() {
                        $('#editBukuForm button[type="submit"]').prop('disabled', true).text(
                            'Menyimpan...');
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#editBukuForm')[0].reset();
                            $('#editBukuModal').modal('hide');
                            location.reload(); // Refresh halaman atau update DataTable
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "Terjadi kesalahan:\n";
                        $.each(errors, function(key, value) {
                            errorMessage += "- " + value + "\n";
                        });
                        alert(errorMessage);
                    },
                    complete: function() {
                        $('#editBukuForm button[type="submit"]').prop('disabled', false).text(
                            'Simpan Perubahan');
                    }
                });
            });



            // Menampilkan preview gambar saat memilih file
            $('#foto').change(function(event) {
                let reader = new FileReader();
                reader.onload = function() {
                    $('#fotoPreview').attr('src', reader.result).show();
                }
                reader.readAsDataURL(event.target.files[0]);
            });
        });
    </script>
@endsection

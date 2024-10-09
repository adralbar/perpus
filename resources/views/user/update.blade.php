@extends('layout.main')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role->nama }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="card-header">
                <h3 class="card-title">Update Data Karyawan</h3>
            </div>

            <div class="card-body">
                <form action="{{ url('user/update/' . $user->npk) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="npk">NPK</label>
                                <input type="number" class="form-control" name="npk" id="npk"
                                    value="{{ old('npk', $user->npk) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Karyawan</label>
                        <input type="text" class="form-control" name="nama" id="nama"
                            value="{{ old('nama', $user->nama) }}" placeholder="Nama Karyawan">
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text" class="form-control" name="no_telp" id="no_telp"
                            value="{{ old('no_telp', $user->no_telp) }}" placeholder="no_telp">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control select2bs4" name="role_id" style="width: 100%;">
                            @foreach ($role as $rl)
                                <option value="{{ $rl }}" {{ $rl == $user->role_id ? 'selected' : '' }}>
                                    {{ $rl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Division</label>
                        <select class="form-control select2bs4" name="division_id" style="width: 100%;">
                            @foreach ($division as $div)
                                <option value="{{ $div }}" {{ $div == $user->division_id ? 'selected' : '' }}>
                                    {{ $div }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Departemen</label>
                        <select class="form-control select2bs4" name="department_id" style="width: 100%;">
                            @foreach ($department as $dept)
                                <option value="{{ $dept }}" {{ $dept == $user->department_id ? 'selected' : '' }}>
                                    {{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <select class="form-control select2bs4" name="section_id" style="width: 100%;">
                            @foreach ($section as $sec)
                                <option value="{{ $sec }}" {{ $sec == $user->section_id ? 'selected' : '' }}>
                                    {{ $sec }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col mt-3">
                            <a href="/user" class="btn btn-danger">Batal</a>
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

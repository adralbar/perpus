@extends('layout.main')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role->nama }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Karyawan</h3>
        </div>

        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>NPK</th>
                        <th>Nama</th>
                        <th>Division</th>
                        <th>Departemen</th>
                        <th>Section</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user->npk }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $division->nama }}</td>
                        <td>{{ $department->nama }}</td>
                        <td>{{ $section->nama }}</td>
                        <td>{{ $user->role_id }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <a href="/user" class="btn btn-info float-right">Kembali</a>
        </div>
    </div>
@endsection

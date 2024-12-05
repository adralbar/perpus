@extends('layout.main   ')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role->nama }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="card-header">
                <p class="pl-3 pb-3 font-weight-bold h3">Detail Data Karyawan</p>
            </div>

            <div class="card-body bg-white">
                <table id="example1" class="table table-bordered table-striped ">
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
                <a href="{{ route('karyawan.index') }}" class="btn btn-info float-right">Kembali</a>
            </div>
        </div>
    </div>
    </div>
@endsection

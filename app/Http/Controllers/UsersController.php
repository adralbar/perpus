<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleModel;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->role_id;
        $sectionId = $user->section_id;

        // Initialize userData as an empty collection
        $userData = collect();

        // Check role ID and fetch data accordingly
        if ($roleId == 2) {
            // Fetch users based on section id if the role is 2
            $userData = User::with('division', 'department', 'section', 'role')
                ->where('section_id', $sectionId)
                ->orderBy('created_at', 'DESC')
                ->get();
        } elseif ($roleId == 1 || $roleId == 6) {
            // Fetch all users if the role is 1 or 6
            $userData = User::with('division', 'department', 'section', 'role')
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        // Fetch other models if needed (optional)
        $role = RoleModel::all();
        $section = SectionModel::all();
        $department = DepartmentModel::all();
        $division = DivisionModel::all();

        // Use the retrieved userData for DataTables response
        return view('user.index', compact('userData', 'role', 'section', 'department', 'division'));
    }



    public function store(Request $request)
    {
        $request->validate(
            [
                'npk' => 'required|unique:users,npk',
                'nama' => 'required',
                'password' => 'required',
                'no_telp' => 'required',
                'section_id' => 'required',
                'department_id' => 'required',
                'division_id' => 'required',
                'role_id' => 'required',
            ],
            [
                'npk.required' => 'NPK wajib diisi.',
                'npk.unique' => 'NPK sudah terdaftar.',
                'nama.required' => 'Nama wajib diisi.',
                'password.required' => 'Password wajib diisi.',
                'no_telp.required' => 'no_telp wajib diisi.',
                'section_id.required' => 'Section wajib dipilih.',
                'department_id.required' => 'Departemen wajib dipilih.',
                'division_id.required' => 'Division wajib dipilih.',
                'role_id.required' => 'Role wajib dipilih.',
            ]
        );
        $data = [
            'npk' => $request->npk,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'no_telp' => $request->no_telp,
            'section_id' => $request->section_id,
            'department_id' => $request->department_id,
            'division_id' => $request->division_id,
            'role_id' => $request->role_id,
        ];
        User::create($data);
        return redirect('user')->with('success', 'User berhasil ditambahkan.');
    }

    public function detail($npk)
    {
        $user = User::where('npk', $npk)->firstOrFail();
        $division = DivisionModel::find($user->division_id); // Ambil semua division
        $department = DepartmentModel::find($user->department_id);
        $section = SectionModel::find($user->section_id); // Ambil semua section
        $role = RoleModel::find($user->role_id);

        return view('user.detail', [
            'user' => $user,
            'division' => $division,
            'department' => $department,
            'section' => $section,
            'role' => $role,
        ]);
    }

    public function edit($npk)
    {
        $user = User::where('npk', $npk)->firstOrFail();
        $division = DivisionModel::find($user->division_id); // Ambil semua division
        $department = DepartmentModel::find($user->department_id);
        $section = SectionModel::find($user->section_id); // Ambil semua section
        $role = RoleModel::find($user->role_id);

        return view('user.update', [
            'user' => $user,
            'division' => $division,
            'department' => $department,
            'section' => $section,
            'role' => $role
        ]);
    }

    public function update(Request $request, $npk)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'nullable|min:6',
            'no_telp' => 'required',
            'division_id' => 'required',
            'department_id' => 'required',
            'section_id' => 'required',
            'role_id' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'no_telp.required' => 'no_telp wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'Division_id.required' => 'Division wajib dipilih.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'Section_id.required' => 'Section wajib dipilih.',
            'role_id.required' => 'Role wajib dipilih.',
        ]);
        $user = User::where('npk', $npk)->firstOrFail();
        $user->nama = $request->nama;
        $user->no_telp = $request->no_telp;
        $user->division_id = $request->division_id;
        $user->department_id = $request->department_id;
        $user->section_id = $request->section_id;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        dd($user->all());
        $user->save();

        return redirect('/user')->with('success', 'Data User berhasil diperbarui.');
    }

    public function destroy($npk)
    {
        $user = User::where('npk', $npk)->firstOrFail();
        $user->delete();

        return redirect('/user')->with('success', 'Data User berhasil dihapus.');
    }
    public function karyawandata()
    {
        $userData = User::with('division', 'department', 'section', 'role')->orderBy('created_at', 'DESC')->get();
        $role = RoleModel::all();
        $section = SectionModel::all(); // Ambil semua section
        $department = DepartmentModel::all(); // Ambil semua departemen
        $division = DivisionModel::all(); // Ambil semua division

        return response()->json([
            'userData' => $userData,
            'roles' => $role,
            'sections' => $section,
            'departments' => $department,
            'divisions' => $division
        ]);
    }
    public function getDepartments($divisionId)
    {
        $departments = DepartmentModel::where('division_id', $divisionId)->get();
        return response()->json($departments);
    }

    public function getSections($departmentId)
    {
        $sections = SectionModel::where('department_id', $departmentId)->get();
        return response()->json($sections);
    }
}

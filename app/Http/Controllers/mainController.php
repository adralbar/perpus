<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleModel;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\Auth;

class mainController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user(); // Get the authenticated user
            $roleId = $user->role_id; // Get the user's role ID
            $sectionId = $user->section_id; // Get the user's section ID

            // Initialize userData as an empty collection
            $userData = collect();

            // Check role ID and fetch data accordingly
            if ($roleId == 2) {
                // Fetch users based on section ID if the role is 2
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

            // Pass roleId to the view
            return view('layout.main', compact('userData', 'role', 'section', 'department', 'division', 'roleId'));
        }
    }
}

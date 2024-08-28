<?php

namespace App\Http\Controllers;

use App\Models\absensico;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class absensiCoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = absensico::orderby('tanggal', 'asc');
        return DataTables::of($data)->addIndexColumn()->make(true);

        $absensico = absensico::all();
        return response()->json($absensico);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

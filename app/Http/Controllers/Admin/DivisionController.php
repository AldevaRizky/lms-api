<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DivisionController extends Controller
{
    /**
     * [Admin] Display a listing of the divisions.
     */
    public function index()
    {
        $divisions = Division::with('manager', 'users')->get();
        return response()->json($divisions, 200);
    }

    /**
     * [Admin] Store a newly created division.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $division = Division::create($validated);
        return response()->json($division, 201);
    }

    /**
     * [Admin] Display the specified division.
     */
    public function show($id)
    {
        $division = Division::with('manager', 'users')->find($id);

        if (!$division) {
            return response()->json(['message' => 'Division not found'], 404);
        }

        return response()->json($division, 200);
    }

    /**
     * [Admin] Update the specified division.
     */
    public function update(Request $request, $id)
    {
        $division = Division::find($id);

        if (!$division) {
            return response()->json(['message' => 'Division not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $division->update($validated);
        return response()->json($division, 200);
    }

    /**
     * [Admin] Remove the specified division.
     */
    public function destroy($id)
    {
        $division = Division::find($id);

        if (!$division) {
            return response()->json(['message' => 'Division not found'], 404);
        }

        $division->delete();
        return response()->json(['message' => 'Division deleted'], 200);
    }
}

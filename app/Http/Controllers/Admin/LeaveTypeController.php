<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * [Admin] Display a listing of leave types.
     */
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return response()->json($leaveTypes);
    }

    /**
     * [Admin] Store a newly created leave type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_document' => 'required|boolean',
            'max_days' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $leaveType = LeaveType::create($validated);
        return response()->json($leaveType, 201);
    }

    /**
     * [Admin] Display the specified leave type.
     */
    public function show(LeaveType $leaveType)
    {
        return response()->json($leaveType);
    }

    /**
     * [Admin] Update the specified leave type.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_document' => 'required|boolean',
            'max_days' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $leaveType->update($validated);
        return response()->json($leaveType);
    }

    /**
     * [Admin] Remove the specified leave type.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    /**
     * [Admin] Display a listing of leave balances.
     */
    public function index()
    {
        $leaveBalances = LeaveBalance::with(['user', 'leaveType'])->get();
        return response()->json($leaveBalances);
    }

    /**
     * [Admin] Store a newly created leave balance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'year' => 'required|integer',
            'max_quota' => 'required|integer',
            'used_days' => 'required|integer',
            'remaining_days' => 'required|integer',
        ]);

        $leaveBalance = LeaveBalance::create($validated);
        return response()->json($leaveBalance, 201);
    }

    /**
     * [Admin] Display the specified leave balance.
     */
    public function show(LeaveBalance $leaveBalance)
    {
        $leaveBalance->load(['user', 'leaveType']);
        return response()->json($leaveBalance);
    }

    /**
     * [Admin] Update the specified leave balance.
     */
    public function update(Request $request, LeaveBalance $leaveBalance)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'year' => 'required|integer',
            'max_quota' => 'required|integer',
            'used_days' => 'required|integer',
            'remaining_days' => 'required|integer',
        ]);

        $leaveBalance->update($validated);
        return response()->json($leaveBalance);
    }

    /**
     * [Admin] Remove the specified leave balance.
     */
    public function destroy(LeaveBalance $leaveBalance)
    {
        $leaveBalance->delete();
        return response()->json(null, 204);
    }
}

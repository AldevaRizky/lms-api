<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    /**
     * [Admin] Display a listing of leave requests.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::with(['user', 'leaveType'])->get();
        return response()->json($leaveRequests);
    }

    /**
     * [Admin] Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|integer',
            'status' => 'required|string',
            'reason' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload if exists
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents', 'public');
            $validated['document_path'] = $path;
        }

        $leaveRequest = LeaveRequest::create($validated);
        return response()->json($leaveRequest, 201);
    }

    /**
     * [Admin] Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['user', 'leaveType']);
        return response()->json($leaveRequest);
    }

    /**
     * [Admin] Update the specified leave request.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|integer',
            'status' => 'required|string',
            'reason' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload if exists and delete old file
        if ($request->hasFile('document')) {
            if ($leaveRequest->document_path) {
                Storage::disk('public')->delete($leaveRequest->document_path);
            }
            $path = $request->file('document')->store('documents', 'public');
            $validated['document_path'] = $path;
        }

        $leaveRequest->update($validated);
        return response()->json($leaveRequest);
    }

    /**
     * [Admin] Remove the specified leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        // Delete the associated document file
        if ($leaveRequest->document_path) {
            Storage::disk('public')->delete($leaveRequest->document_path);
        }
        
        $leaveRequest->delete();
        return response()->json(null, 204);
    }
}

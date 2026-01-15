<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('leaves.leaves_type', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'default_days' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7'
        ]);

        LeaveType::create($request->all());

        return redirect()->route('leave-types.index')
                         ->with('success', 'Leave Type created successfully.');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'default_days' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7'
        ]);

        $leaveType->update($request->all());

        return redirect()->route('leave-types.index')
                         ->with('success', 'Leave Type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        // Optional: Check if used in employee_leaves before deleting
        if($leaveType->leaves()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete: This leave type is currently used by employees.');
        }

        $leaveType->delete();

        return redirect()->route('leave-types.index')
                         ->with('success', 'Leave Type deleted successfully.');
    }
}
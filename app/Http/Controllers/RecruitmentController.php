<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentController extends Controller
{
    /**
     * Display the index page.
     */
    public function index()
    {
        $employees = Employee::all();
        return view('recruitments.index', compact('employees'));
    }

    /**
     * AJAX data for DataTables.
     */
    public function list(Request $request)
    {
        $query = Recruitment::with('employee')->select('recruitments.*');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('position')) {
            $query->where('position_applied', 'like', '%' . $request->position . '%');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            // REMOVED the editColumn('status') from here to keep data clean for JS
            ->make(true);
    }

    /**
     * Store application (Photo only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'resume' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('resume')->store('resumes', 'public');

        Recruitment::create(array_merge($request->all(), [
            'resume_file_path' => $path,
            'status' => 'pending'
        ]));

        return redirect()->route('recruitments.index')->with('success', 'Application saved.');
    }

    public function show(Recruitment $recruitment)
    {
        return view('recruitments.show', compact('recruitment'));
    }

    public function edit(Recruitment $recruitment)
    {
        return view('recruitments.edit', compact('recruitment'));
    }

    public function update(Request $request, Recruitment $recruitment)
    {
        $request->validate([
            'name' => 'required|string',
            'resume' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('resume');

        if ($request->hasFile('resume')) {
            if ($recruitment->resume_file_path) {
                Storage::disk('public')->delete($recruitment->resume_file_path);
            }
            $data['resume_file_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $recruitment->update($data);
        return redirect()->route('recruitments.index')->with('success', 'Information updated.');
    }

    /**
     * Handle one-to-one link with Employee.
     */
    public function updateStatus(Request $request, Recruitment $recruitment)
    {
        $request->validate([
            'status' => 'required|in:pending,declined,accepted',
            'employee_id' => 'nullable|exists:employees,id|unique:recruitments,employee_id,' . $recruitment->id
        ]);

        $employeeId = ($request->status === 'accepted') ? $request->employee_id : null;

        $recruitment->update([
            'status' => $request->status,
            'employee_id' => $employeeId
        ]);

        return back()->with('success', 'Status and link updated.');
    }

    public function downloadResume(Recruitment $recruitment)
    {
        if (!$recruitment->resume_file_path) return abort(404);
        return Storage::disk('public')->download($recruitment->resume_file_path);
    }

    public function destroy(Recruitment $recruitment)
    {
        if ($recruitment->resume_file_path) {
            Storage::disk('public')->delete($recruitment->resume_file_path);
        }
        $recruitment->delete();
        return back()->with('success', 'Record deleted.');
    }
}

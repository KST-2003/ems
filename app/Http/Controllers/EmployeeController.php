<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeExperience;
use App\Models\EmployeeCertificate;
use App\Models\EmployeeCriminalRecord;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\EmployeeAbsence;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function webIndex()
    {
        return view('employees.index');
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('employees', 'public');
            $validated['profile_image'] = basename($path);
        }

        $employee = Employee::create($validated);

        if (!empty($validated['experiences'])) {
            $experiences = array_filter($validated['experiences'], function ($experience) {
                return !empty($experience['position']) || 
                       !empty($experience['department']) || 
                       !empty($experience['from_date']) || 
                       !empty($experience['location']);
            });
            foreach ($experiences as $experience) {
                EmployeeExperience::create([
                    'employee_id' => $employee->id,
                    'position' => $experience['position'] ?? null,
                    'department' => $experience['department'] ?? null,
                    'from_date' => $experience['from_date'] ?? null,
                    'to_date' => $experience['is_current'] ? null : ($experience['to_date'] ?? null),
                    'is_current' => $experience['is_current'] ?? false,
                    'location' => $experience['location'] ?? null,
                ]);
            }
        }

        if (!empty($validated['certificates'])) {
            $certificates = array_filter($validated['certificates'], function ($certificate) {
                return !empty($certificate['certificate_name']) || 
                       !empty($certificate['issue_date']) || 
                       !empty($certificate['issuer']) || 
                       !empty($certificate['description']) || 
                       !empty($certificate['file']);
            });
            foreach ($certificates as $index => $certificate) {
                $filePath = null;
                if ($request->hasFile("certificates.$index.file")) {
                    $filePath = $request->file("certificates.$index.file")->store('certificates', 'public');
                    $filePath = basename($filePath);
                }
                EmployeeCertificate::create([
                    'employee_id' => $employee->id,
                    'certificate_name' => $certificate['certificate_name'] ?? null,
                    'issue_date' => $certificate['issue_date'] ?? null,
                    'issuer' => $certificate['issuer'] ?? null,
                    'description' => $certificate['description'] ?? null,
                    'file_path' => $filePath,
                ]);
            }
        }

        if (!empty($validated['criminal_records'])) {
            $criminalRecords = array_filter($validated['criminal_records'], function ($record) {
                return !empty($record['description']) || !empty($record['file']);
            });
            foreach ($criminalRecords as $index => $record) {
                $filePath = null;
                if ($request->hasFile("criminal_records.$index.file")) {
                    $filePath = $request->file("criminal_records.$index.file")->store('criminal_records', 'public');
                    $filePath = basename($filePath);
                }
                EmployeeCriminalRecord::create([
                    'employee_id' => $employee->id,
                    'description' => $record['description'] ?? null,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('employees.index')->with('success', __('messages.employee_created'));
    }

    public function show(Employee $employee)
    {
        $employee->load('experiences', 'certificates', 'criminalRecords', 'attendances', 'leaves', 'absences');
        
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('experiences', 'certificates', 'criminalRecords');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateRequest($request, $employee->id);

        if ($request->hasFile('profile_image')) {
            if ($employee->profile_image) {
                Storage::disk('public')->delete('employees/' . $employee->profile_image);
            }
            $path = $request->file('profile_image')->store('employees', 'public');
            $validated['profile_image'] = basename($path);
        }

        $employee->update($validated);

        $employee->experiences()->delete();
        if (!empty($validated['experiences'])) {
            $experiences = array_filter($validated['experiences'], function ($experience) {
                return !empty($experience['position']) || 
                       !empty($experience['department']) || 
                       !empty($experience['from_date']) || 
                       !empty($experience['location']);
            });
            foreach ($experiences as $experience) {
                EmployeeExperience::create([
                    'employee_id' => $employee->id,
                    'position' => $experience['position'] ?? null,
                    'department' => $experience['department'] ?? null,
                    'from_date' => $experience['from_date'] ?? null,
                    'to_date' => $experience['is_current'] ? null : ($experience['to_date'] ?? null),
                    'is_current' => $experience['is_current'] ?? false,
                    'location' => $experience['location'] ?? null,
                ]);
            }
        }

        $employee->certificates()->delete();
        if (!empty($validated['certificates'])) {
            $certificates = array_filter($validated['certificates'], function ($certificate) {
                return !empty($certificate['certificate_name']) || 
                       !empty($certificate['issue_date']) || 
                       !empty($certificate['issuer']) || 
                       !empty($certificate['description']) || 
                       !empty($certificate['file']);
            });
            foreach ($certificates as $index => $certificate) {
                $filePath = $certificate['existing_file'] ?? null;
                if ($request->hasFile("certificates.$index.file")) {
                    if ($filePath) {
                        Storage::disk('public')->delete('certificates/' . $filePath);
                    }
                    $filePath = $request->file("certificates.$index.file")->store('certificates', 'public');
                    $filePath = basename($filePath);
                }
                EmployeeCertificate::create([
                    'employee_id' => $employee->id,
                    'certificate_name' => $certificate['certificate_name'] ?? null,
                    'issue_date' => $certificate['issue_date'] ?? null,
                    'issuer' => $certificate['issuer'] ?? null,
                    'description' => $certificate['description'] ?? null,
                    'file_path' => $filePath,
                ]);
            }
        }

        $employee->criminalRecords()->delete();
        if (!empty($validated['criminal_records'])) {
            $criminalRecords = array_filter($validated['criminal_records'], function ($record) {
                return !empty($record['description']) || !empty($record['file']);
            });
            foreach ($criminalRecords as $index => $record) {
                $filePath = $record['existing_file'] ?? null;
                if ($request->hasFile("criminal_records.$index.file")) {
                    if ($filePath) {
                        Storage::disk('public')->delete('criminal_records/' . $filePath);
                    }
                    $filePath = $request->file("criminal_records.$index.file")->store('criminal_records', 'public');
                    $filePath = basename($filePath);
                }
                EmployeeCriminalRecord::create([
                    'employee_id' => $employee->id,
                    'description' => $record['description'] ?? null,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()->route('employees.index')->with('success', __('messages.employee_updated'));
    }

    public function destroy(Employee $employee)
    {
        if ($employee->profile_image) {
            Storage::disk('public')->delete('employees/' . $employee->profile_image);
        }
        foreach ($employee->certificates as $certificate) {
            if ($certificate->file_path) {
                Storage::disk('public')->delete('certificates/' . $certificate->file_path);
            }
        }
        foreach ($employee->criminalRecords as $record) {
            if ($record->file_path) {
                Storage::disk('public')->delete('criminal_records/' . $record->file_path);
            }
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success', __('messages.employee_deleted'));
    }

    public function list(Request $request)
    {
        try {
            $employees = Employee::select(['id', 'employee_id', 'name', 'email', 'phone', 'gender', 'profile_image', 'department']);
            if ($request->has('min_experience')) {
                $employees->whereHas('experiences', function ($query) use ($request) {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, from_date, COALESCE(to_date, NOW())) >= ?', [$request->min_experience]);
                });
            }
            return DataTables::of($employees)
                ->addColumn('#', function ($row) {
                    static $i = 1;
                    return $i++;
                })
                ->addColumn('profile_image', function ($row) {
                    return '<img src="' . $row->profile_image_url . '" alt="Profile" width="50" height="50" class="rounded-circle">';
                })
                ->addColumn('gender', function ($row) {
                    return $row->gender ? ucfirst($row->gender) : '-';
                })
                ->addColumn('experience_years', function ($row) {
                    return $row->total_experience_years . ' years';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('employees.show', $row->id) . '" class="btn btn-sm btn-info">' . __('messages.view') . '</a>
                        <a href="' . route('employees.edit', $row->id) . '" class="btn btn-sm btn-warning">' . __('messages.edit') . '</a>
                        <form action="' . route('employees.destroy', $row->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'' . __('messages.confirm_delete') . '\')">' . __('messages.delete') . '</button>
                        </form>';
                })
                ->rawColumns(['profile_image', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('DataTables error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    private function validateRequest(Request $request, $employeeId = null)
    {
        return $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employeeId,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email,' . $employeeId,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'dob' => 'nullable|date',
            'nationality' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'nrc' => 'nullable|string',
            'spouse_name' => 'nullable|string',
            'children_names' => 'nullable|string',
            'address' => 'nullable|string',
            'education' => 'nullable|string',
            'current_position' => 'nullable|string',
            'salary' => 'nullable|string|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
            'department' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'experiences' => 'nullable|array',
            'experiences.*.position' => 'required_with:experiences.*.department,experiences.*.from_date,experiences.*.location|string|nullable',
            'experiences.*.department' => 'required_with:experiences.*.position,experiences.*.from_date,experiences.*.location|string|nullable',
            'experiences.*.from_date' => 'required_with:experiences.*.position,experiences.*.department,experiences.*.location|date|nullable',
            'experiences.*.to_date' => 'nullable|date',
            'experiences.*.is_current' => 'nullable|boolean',
            'experiences.*.location' => 'required_with:experiences.*.position,experiences.*.department,experiences.*.from_date|string|nullable',
            'certificates' => 'nullable|array',
            'certificates.*.certificate_name' => 'required_with:certificates.*.issue_date,certificates.*.issuer|string|nullable',
            'certificates.*.issue_date' => 'required_with:certificates.*.certificate_name,certificates.*.issuer|date|nullable',
            'certificates.*.issuer' => 'required_with:certificates.*.certificate_name,certificates.*.issue_date|string|nullable',
            'certificates.*.description' => 'nullable|string',
            'certificates.*.file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'certificates.*.existing_file' => 'nullable|string',
            'criminal_records' => 'nullable|array',
            'criminal_records.*.description' => 'nullable|string',
            'criminal_records.*.file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'criminal_records.*.existing_file' => 'nullable|string',
        ], [
            'experiences.*.position.required_with' => __('messages.experience_position_required'),
            'experiences.*.department.required_with' => __('messages.experience_department_required'),
            'experiences.*.from_date.required_with' => __('messages.experience_from_date_required'),
            'experiences.*.location.required_with' => __('messages.experience_location_required'),
            'certificates.*.certificate_name.required_with' => __('messages.certificate_name_required'),
            'certificates.*.issue_date.required_with' => __('messages.certificate_issue_date_required'),
            'certificates.*.issuer.required_with' => __('messages.certificate_issuer_required'),
        ]);
    }
}
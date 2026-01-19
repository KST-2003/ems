<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeExperience;
use App\Models\EmployeeCertificate;
use App\Models\EmployeeCriminalRecord;
use App\Models\EmployeeChild;
use App\Models\EmployeeEducation;
use App\Models\EmployeeTraining;
use App\Models\EmployeePastExperience;
use App\Models\EmployeeRelative;
use App\Models\PersonnelAction;
use App\Models\EmployeeServiceRecord;
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

    /**
     * Show single employee details
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'children',
            'relatives',
            'educations',
            'trainings',
            'pastExperiences',
            'experiences',
            'personnelActions',
            'serviceRecord', // singular
            'certificates',
            'criminalRecords'
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Edit form with pre-filled data
     */
    public function edit(Employee $employee)
    {
        $employee->load([
            'children',
            'relatives',
            'educations',
            'trainings',
            'pastExperiences',
            'experiences',
            'personnelActions',
            'serviceRecord', // singular
            'certificates',
            'criminalRecords'
        ]);

        return view('employees.edit', compact('employee'));
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

        $this->saveRelatedRecords($employee, $validated, $request);

        return redirect()->route('employees.index')->with('success', __('messages.employee_created'));
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

        $this->deleteRelatedRecords($employee);
        $this->saveRelatedRecords($employee, $validated, $request);

        return redirect()->route('employees.index')->with('success', __('messages.employee_updated'));
    }

    public function destroy(Employee $employee)
    {
        // Delete profile image
        if ($employee->profile_image) {
            Storage::disk('public')->delete('employees/' . $employee->profile_image);
        }

        // Delete certificate files
        foreach ($employee->certificates as $certificate) {
            if ($certificate->file_path) {
                Storage::disk('public')->delete('certificates/' . $certificate->file_path);
            }
        }

        // Delete criminal record files
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
            $employees = Employee::select(['id', 'employee_id', 'name', 'phone', 'department']);

            return DataTables::of($employees)
                ->addColumn('#', function ($row) {
                    static $i = 1;
                    return $i++;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('employees.show', $row->id) . '" class="btn btn-sm btn-info">' . __('messages.view') . '</a>
                        <a href="' . route('employees.edit', $row->id) . '" class="btn btn-sm btn-warning">' . __('messages.edit') . '</a>
                        <a href="' . route('employees.print.select', $row->id) . '" class="btn btn-sm btn-secondary">Print</a>
                        <form action="' . route('employees.destroy', $row->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'' . __('messages.confirm_delete') . '\')">' . __('messages.delete') . '</button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('DataTables error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Delete all related records (used in update)
     */
    private function deleteRelatedRecords(Employee $employee)
    {
        $employee->children()->delete();
        $employee->educations()->delete();
        $employee->trainings()->delete();
        $employee->pastExperiences()->delete();
        $employee->relatives()->delete();
        $employee->personnelActions()->delete();
        $employee->serviceRecord()->delete(); // singular
        $employee->experiences()->delete();
        $employee->certificates()->delete();
        $employee->criminalRecords()->delete();
    }

    /**
     * Save all related records (used in store + update)
     */
    private function saveRelatedRecords(Employee $employee, array $validated, Request $request)
    {
        // Children
        if (!empty($validated['children'])) {
            foreach ($validated['children'] as $child) {
                if (!empty($child['name'])) {
                    EmployeeChild::create([
                        'employee_id' => $employee->id,
                        'name' => $child['name'],
                        'date_of_birth' => $child['date_of_birth'] ?? null,
                    ]);
                }
            }
        }

        // Education
        if (!empty($validated['educations'])) {
            foreach ($validated['educations'] as $edu) {
                if (!empty($edu['institution_name']) || !empty($edu['degree_certificate'])) {
                    EmployeeEducation::create([
                        'employee_id' => $employee->id,
                        'type' => $edu['type'] ?? 'other',
                        'institution_name' => $edu['institution_name'] ?? null,
                        'degree_certificate' => $edu['degree_certificate'] ?? null,
                        'field_of_study' => $edu['field_of_study'] ?? null,
                        'date' => $edu['date'] ?? null,
                        'remark' => $edu['remark'] ?? null,
                    ]);
                }
            }
        }

        // Training
        if (!empty($validated['trainings'])) {
            foreach ($validated['trainings'] as $training) {
                if (!empty($training['course_name'])) {
                    EmployeeTraining::create([
                        'employee_id' => $employee->id,
                        'training_type' => $training['training_type'] ?? 'other',
                        'course_name' => $training['course_name'],
                        'location' => $training['location'] ?? null,
                        'start_date' => $training['start_date'] ?? null,
                        'end_date' => $training['end_date'] ?? null,
                    ]);
                }
            }
        }

        // Past Experiences
        if (!empty($validated['past_experiences'])) {
            foreach ($validated['past_experiences'] as $past) {
                if (!empty($past['position'])) {
                    EmployeePastExperience::create([
                        'employee_id' => $employee->id,
                        'position' => $past['position'],
                        'salary' => $past['salary'] ?? null,
                        'location' => $past['location'] ?? null,
                        'start_date' => $past['start_date'] ?? null,
                        'end_date' => $past['end_date'] ?? null,
                        'remark' => $past['remark'] ?? null,
                        'life_insurance' => $past['life_insurance'] ?? 'no',
                    ]);
                }
            }
        }

        // Relatives
        if (!empty($validated['relatives'])) {
            foreach ($validated['relatives'] as $relative) {
                if (!empty($relative['name'])) {
                    EmployeeRelative::create([
                        'employee_id' => $employee->id,
                        'name' => $relative['name'],
                        'relation' => $relative['relation'] ?? null,
                        'job' => $relative['job'] ?? null,
                        'location' => $relative['location'] ?? null,
                    ]);
                }
            }
        }

        // Personnel Actions
        if (!empty($validated['personnel_actions'])) {
            foreach ($validated['personnel_actions'] as $action) {
                if (!empty($action['type'])) {
                    PersonnelAction::create([
                        'employee_id' => $employee->id,
                        'type' => $action['type'],
                        'position' => $action['position'] ?? null,
                        'department' => $action['department'] ?? null,
                        'location' => $action['location'] ?? null,
                        'start_date' => $action['start_date'] ?? null,
                        'end_date' => $action['end_date'] ?? null,
                        'reason' => $action['reason'] ?? null,
                        'remark' => $action['remark'] ?? null,
                    ]);
                }
            }
        }

        // Current Company Experiences (နိုင်ငံ့ဝန်ထမ်းတာဝန်ထမ်းဆောင်မှုမှတ်တမ်း)
        if (!empty($validated['experiences'])) {
            foreach ($validated['experiences'] as $experience) {
                if (!empty($experience['position']) || !empty($experience['department']) || !empty($experience['from_date'])) {
                    EmployeeExperience::create([
                        'employee_id' => $employee->id,
                        'company_name' => $experience['company_name'] ?? null,
                        'position' => $experience['position'] ?? null,
                        'department' => $experience['department'] ?? null,
                        'from_date' => $experience['from_date'] ?? null,
                        'to_date' => ($experience['is_current'] ?? false) ? null : ($experience['to_date'] ?? null),
                        'is_current' => $experience['is_current'] ?? false,
                        'location' => $experience['location'] ?? null,
                    ]);
                }
            }
        }

        // လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့ (Permanent Appointment Record)
        if (!empty($validated['service_record'])) {
            $sr = $validated['service_record'];

            if (!empty($sr['grade']) || !empty($sr['recruited_date'])) {
                EmployeeServiceRecord::updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'grade' => $sr['grade'] ?? null,
                        'recruited_date' => $sr['recruited_date'] ?? null,
                        'remark' => $sr['remark'] ?? null,
                    ]
                );
            }
        }

        // Certificates
        if ($request->has('certificates')) {
            foreach ($request->input('certificates', []) as $index => $certificate) {
                if (empty($certificate['certificate_name']) &&
                    empty($certificate['issue_date']) &&
                    empty($certificate['issuer']) &&
                    empty($certificate['description']) &&
                    !$request->hasFile("certificates.$index.file")) {
                    continue;
                }

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

        // Criminal Records
        if ($request->has('criminal_records')) {
            foreach ($request->input('criminal_records', []) as $index => $record) {
                if (empty($record['description']) && !$request->hasFile("criminal_records.$index.file")) {
                    continue;
                }

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
    }

    private function validateRequest(Request $request, $employeeId = null)
    {
        return $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employeeId,
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:10240',
            'mm_dob' => 'nullable|string',
            'eng_dob' => 'nullable|date',
            'nationality' => 'nullable|string',
            'religion' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'nrc' => 'nullable|string',
            'spouse_name' => 'nullable|string',
            'spouse_job' => 'nullable|string',
            'spouse_job_place' => 'nullable|string',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'current_position' => 'nullable|string',
            'salary' => 'nullable|string',
            'department' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'lang_proficiency' => 'nullable|string',
            'hobby' => 'nullable|string',

            // Children
            'children' => 'nullable|array',
            'children.*.name' => 'required_with:children.*.date_of_birth|string|nullable',
            'children.*.date_of_birth' => 'nullable|date',

            // Education
            'educations' => 'nullable|array',
            'educations.*.type' => 'nullable|in:school,uni,other',
            'educations.*.institution_name' => 'nullable|string',
            'educations.*.degree_certificate' => 'nullable|string',
            'educations.*.field_of_study' => 'nullable|string',
            'educations.*.date' => 'nullable|date',
            'educations.*.remark' => 'nullable|string',

            // Training
            'trainings' => 'nullable|array',
            'trainings.*.training_type' => 'nullable|in:domestic,foreign,other',
            'trainings.*.course_name' => 'required_with:trainings.*.start_date|string|nullable',
            'trainings.*.location' => 'nullable|string',
            'trainings.*.start_date' => 'nullable|date',
            'trainings.*.end_date' => 'nullable|date',

            // Past Experiences
            'past_experiences' => 'nullable|array',
            'past_experiences.*.position' => 'required_with:past_experiences.*.salary|string|nullable',
            'past_experiences.*.salary' => 'nullable|string',
            'past_experiences.*.location' => 'nullable|string',
            'past_experiences.*.start_date' => 'nullable|date',
            'past_experiences.*.end_date' => 'nullable|date',
            'past_experiences.*.remark' => 'nullable|string',
            'past_experiences.*.life_insurance' => 'nullable|in:yes,no',

            // Relatives
            'relatives' => 'nullable|array',
            'relatives.*.name' => 'required_with:relatives.*.relation|string|nullable',
            'relatives.*.relation' => 'nullable|string',
            'relatives.*.job' => 'nullable|string',
            'relatives.*.location' => 'nullable|string',

            // Personnel Actions
            'personnel_actions' => 'nullable|array',
            'personnel_actions.*.type' => 'required_with:personnel_actions.*.position|in:recruit,promote,demote,transfer,punishment,partnership',
            'personnel_actions.*.position' => 'nullable|string',
            'personnel_actions.*.department' => 'nullable|string',
            'personnel_actions.*.location' => 'nullable|string',
            'personnel_actions.*.start_date' => 'nullable|date',
            'personnel_actions.*.end_date' => 'nullable|date',
            'personnel_actions.*.reason' => 'nullable|string',
            'personnel_actions.*.remark' => 'nullable|string',

            // Current Company Duty History (နိုင်ငံ့ဝန်ထမ်းတာဝန်ထမ်းဆောင်မှုမှတ်တမ်း)
            'experiences' => 'nullable|array',
            'experiences.*.company_name' => 'nullable|string',
            'experiences.*.position' => 'nullable|string',
            'experiences.*.department' => 'nullable|string',
            'experiences.*.from_date' => 'nullable|date',
            'experiences.*.to_date' => 'nullable|date',
            'experiences.*.is_current' => 'nullable|boolean',
            'experiences.*.location' => 'nullable|string',

            // လက်ရှိဝန်ထမ်းအဖွဲ့ဝင်သည့်နေ့ (Permanent Appointment Info)
            'service_record' => 'nullable|array',
            'service_record.grade' => 'nullable|in:junior,senior,selection,higher',
            'service_record.recruited_date' => 'nullable|date',
            'service_record.remark' => 'nullable|string',

            // Certificates
            'certificates' => 'nullable|array',
            'certificates.*.certificate_name' => 'nullable|string',
            'certificates.*.issue_date' => 'nullable|date',
            'certificates.*.issuer' => 'nullable|string',
            'certificates.*.description' => 'nullable|string',
            'certificates.*.file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'certificates.*.existing_file' => 'nullable|string',

            // Criminal Records
            'criminal_records' => 'nullable|array',
            'criminal_records.*.description' => 'nullable|string',
            'criminal_records.*.file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
            'criminal_records.*.existing_file' => 'nullable|string',
        ]);
    }
    public function securityIndex()
    {
        // Fetch all employees to assign roles to them
        $employees = Employee::select('id', 'name', 'employee_id', 'department')->get();
        
        // Define system roles for your 200+ employee organization
        $roles = [
            'Admin' => 'Full access to all modules and BOD reports.',
            'Manager' => 'Can manage department attendance and leaves.',
            'Staff' => 'Standard access to personal records.'
        ];

        return view('employees.security', compact('employees', 'roles'));
    }
}
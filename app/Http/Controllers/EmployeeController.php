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
use App\Models\EmployeePersonalRecord;
use App\Models\EmployeeRelative;
use App\Models\PersonnelAction;
use App\Models\EmployeeServiceRecord;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Models\EmployeeSpouse;
use Illuminate\Support\Facades\DB;

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
            'serviceRecord',
            'certificates',
            'criminalRecords',
            'personalRecord',   // Background history (Tab 5)
            'abroads',          // Foreign travel (Tab 5)
            'parentSiblings',   // Extended family (Tab 2)
            'spouse'
        ]);

        return view('employees.show', compact('employee'));
    }

    /**
     * Edit form with pre-filled data
     */
    public function edit(Employee $employee)
    {
        // Load EVERY relationship needed for the 5 Tabs
        $employee->load([
            'children',
            'relatives',
            'educations',
            'trainings',
            'pastExperiences',
            'experiences',
            'personnelActions',
            'serviceRecord',
            'certificates',
            'criminalRecords',
            'personalRecord',   // MISSING: Tab 5 Background info
            'spouse',           // MISSING: Tab 2 Spouse info
            'abroads',          // MISSING: Tab 5 Foreign travel
            'parentSiblings'    // MISSING: Tab 2 Extended family
        ]);

        // NULL SAFETY: Ensure objects exist so the view doesn't crash when accessing properties
        if (!$employee->serviceRecord) {
            $employee->setRelation('serviceRecord', new EmployeeServiceRecord());
        }

        if (!$employee->personalRecord) {
            $employee->setRelation('personalRecord', new EmployeePersonalRecord());
        }

        if (!$employee->spouse) {
            // Using a real Model instance is better than (object) because it handles $fillable correctly
            $employee->setRelation('spouse', new EmployeeSpouse());
        }

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

        return DB::transaction(function () use ($validated, $request) {
            // These keys are filtered OUT because they belong to OTHER tables.
            // Physical attributes (height, weight, etc.) are NOT in this list, 
            // so they WILL be sent to the Employee::create() method.
            $keysForOtherTables = [
                'spouse_name',
                'spouse_job',
                'spouse_job_place',
                'spouse_relatives',
                'family_tree',
                'abroads',
                'children',
                'educations',
                'trainings',
                'past_experiences',
                'relatives',
                'personnel_actions',
                'experiences',
                'service_record',
                'certificates',
                'criminal_records',
                'schools',
                'latest_school',
                'school_voluntary',
                'hobbies',
                'citizen_duties',
                'has_criminal_rec'
            ];

            $employeeData = Arr::except($validated, $keysForOtherTables);
            $employee = Employee::create($employeeData);

            $this->saveRelatedRecords($employee, $validated, $request);

            return redirect()->route('employees.index')->with('success', __('messages.employee_created'));
        });
    }

    public function update(Request $request, Employee $employee)
    {
        // 1. Validate the incoming request
        $validated = $this->validateRequest($request, $employee->id);
        try {
            return DB::transaction(function () use ($validated, $request, $employee) {

                // 2. Handle Profile Image Update
                if ($request->hasFile('profile_image')) {
                    if ($employee->profile_image) {
                        Storage::disk('public')->delete('employees/' . $employee->profile_image);
                    }
                    $path = $request->file('profile_image')->store('employees', 'public');
                    $validated['profile_image'] = basename($path);
                }

                // 3. Separate main Employee data from relational data
                // We exclude all keys that belong to Tab 2, 3, 4, and 5 tables
                $relationalKeys = [
                    'spouse_name',
                    'spouse_job',
                    'spouse_job_place',
                    'spouse_relatives',
                    'family_tree',
                    'abroads',
                    'children',
                    'educations',
                    'trainings',
                    'past_experiences',
                    'relatives',
                    'personnel_actions',
                    'experiences',
                    'service_record',
                    'certificates',
                    'criminal_records',
                    'schools',
                    'latest_school',
                    'school_voluntary',
                    'hobbies',
                    'citizen_duties',
                    'has_criminal_rec',
                    'jobs_dept',
                    'refugee',
                    'jobtransfer_desc',
                    'foreign_friends_desc',
                    'relatives_officials',
                    'referal_officials'
                ];

                $employeeData = Arr::except($validated, $relationalKeys);

                // 4. Update the main Employee table
                $employee->update($employeeData);

                // 5. Cleanup and Sync Relational Data
                // deleteRelatedRecords should clear children, educations, experiences, etc.
                $this->deleteRelatedRecords($employee);

                // saveRelatedRecords will re-insert children/experiences 
                // and updateOrInsert the Personal Record (Tab 5)
                $this->saveRelatedRecords($employee, $validated, $request);

                //Log::info($validated);
                return redirect()->route('employees.index')->with('success', __('messages.employee_updated'));
            });
        } catch (\Exception $e) {
            // This is how you check for errors:
            Log::error('Employee Update Failed: ' . $e->getMessage());

            // Return back to the form with the error message and old input
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
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

        DB::table('employee_spouses')->where('employee_id', $employee->id)->delete();

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
        // 1. Update/Insert Personal Records (Background Check Info)
        DB::table('employee_personal_records')->updateOrInsert(
            ['employee_id' => $employee->id],
            [
                'schools' => $validated['schools'] ?? null,
                'latest_school' => $validated['latest_school'] ?? null,
                'school_voluntary' => $validated['school_voluntary'] ?? null,
                'hobbies' => $validated['hobbies'] ?? null,
                'jobs_dept' => $validated['jobs_dept'] ?? null,
                'refugee' => $validated['refugee'] ?? null,
                'jobtransfer_desc' => $validated['jobtransfer_desc'] ?? null,
                'citizen_duties' => $validated['citizen_duties'] ?? null,
                'relatives_officials' => $validated['relatives_officials'] ?? null,
                'foreign_friends_desc' => $validated['foreign_friends_desc'] ?? null,
                'referal_officials' => $validated['referal_officials'] ?? null,
                'has_criminal_rec' => $validated['has_criminal_rec'] ?? false,
                'updated_at' => now(),
            ]
        );

        // Spouse Information
        // Maps to the new employee_spouses table
        if (!empty($validated['spouse_name'])) {
            DB::table('employee_spouses')->updateOrInsert(
                ['employee_id' => $employee->id],
                [
                    'name' => $validated['spouse_name'],
                    'job' => $validated['spouse_job'] ?? null,
                    'hometown' => $validated['spouse_job_place'] ?? null,
                    'updated_at' => now(),
                ]
            );
        }

        // Save Spouse's Relatives (Siblings, Paternal, Maternal)
        if (!empty($validated['spouse_relatives'])) {
            DB::table('spouse_relatives')->where('employee_id', $employee->id)->delete();
            foreach ($validated['spouse_relatives'] as $s_relative) {
                if (!empty($s_relative['name'])) {
                    DB::table('spouse_relatives')->insert([
                        'employee_id' => $employee->id,
                        'type' => $s_relative['type'], // sibling, paternal, maternal
                        'name' => $s_relative['name'],
                        'nationality_religion' => $s_relative['nationality_religion'] ?? null,
                        'hometown' => $s_relative['hometown'] ?? null,
                        'job' => $s_relative['job'] ?? null,
                        'address' => $s_relative['address'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. One-to-Many Relationships (DELETE existing first to prevent duplicates)
        $employee->children()->delete();
        if (!empty($validated['children'])) {
            foreach ($validated['children'] as $child) {
                if (!empty($child['name'])) {
                    $employee->children()->create($child);
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
                        'nationality_religion' => $relative['nationality_religion'] ?? null,
                        'hometown' => $relative['hometown'] ?? null,
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

        // 2. Clear and Save Abroads (Section 10 from your image)
        $employee->abroads()->delete();
        if (!empty($validated['abroads'])) {
            foreach ($validated['abroads'] as $abroad) {
                if (!empty($abroad['country'])) {
                    $employee->abroads()->create([
                        'country'        => $abroad['country'],
                        'reason'         => $abroad['reason'] ?? null,
                        'host_name'      => $abroad['host_name'] ?? null, // Match DB name
                        'departure_date' => $abroad['departure_date'] ?? null,
                        'arrival_date'   => $abroad['arrival_date'] ?? null,
                    ]);
                }
            }
        }

        // Parent/Sibling Expansion (Template C Family Tree)
        if (!empty($validated['family_tree'])) {
            $employee->parentSiblings()->delete();
            foreach ($validated['family_tree'] as $member) {
                if (!empty($member['name'])) {
                    $employee->parentSiblings()->create([
                        'side' => $member['side'], // father or mother
                        'name' => $member['name'],
                        'nationality_religion' => $member['nationality_religion'] ?? null,
                        'hometown' => $member['hometown'] ?? null,
                        'relation' => $member['relation'] ?? null,
                        'job' => $member['job'] ?? null,
                        'location' => $member['location'] ?? null,
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
                if (
                    empty($certificate['certificate_name']) &&
                    empty($certificate['issue_date']) &&
                    empty($certificate['issuer']) &&
                    empty($certificate['description']) &&
                    !$request->hasFile("certificates.$index.file")
                ) {
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

        if ($request->has('criminal_records')) {
            foreach ($request->input('criminal_records', []) as $index => $crim) {
                $filePath = $request->hasFile("criminal_records.$index.file")
                    ? basename($request->file("criminal_records.$index.file")->store('criminal_records', 'public'))
                    : ($crim['existing_file'] ?? null);

                if ($filePath || !empty($crim['description'])) {
                    EmployeeCriminalRecord::create([
                        'employee_id' => $employee->id,
                        'name' => $crim['name'] ?? null,
                        'start_date' => $crim['start_date'] ?? null,
                        'end_date' => $crim['end_date'] ?? null,
                        'description' => $crim['description'] ?? null,
                        'file_path' => $filePath,
                    ]);
                }
            }
        }
    }

    private function validateRequest(Request $request, $employeeId = null)
    {
        return $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employeeId,
            'name' => 'required|string|max:255',
            'home_name' => 'nullable|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:10240',
            'mm_dob' => 'nullable|string',
            'eng_dob' => 'nullable|date',
            'nationality' => 'nullable|string',
            'religion' => 'nullable|string',
            'nrc' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'height' => 'nullable|string',
            'hair_color' => 'nullable|string',
            'eye_color' => 'nullable|string',
            'skin_color' => 'nullable|string',
            'notable_trade' => 'nullable|string',
            'weight' => 'nullable|string',
            'pob' => 'nullable|string',

            //Parent Info
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'spouse_name' => 'nullable|string',
            'spouse_job' => 'nullable|string',
            'spouse_job_place' => 'nullable|string',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'current_position' => 'nullable|string',
            'salary' => 'nullable|string',
            'department' => 'nullable|string',
            'department_place' => 'nullable|string',
            'lang_proficiency' => 'nullable|string',
            'hobbies' => 'nullable|string',
            'is_parent_citizen' => 'nullable|string',

            // ADD THIS FOR PARENT SIBLINGS
            'family_tree' => 'nullable|array',
            'family_tree.*.side' => 'required|in:father,mother',
            'family_tree.*.name' => 'required|string',
            'family_tree.*.relation' => 'nullable|string',
            'family_tree.*.job' => 'nullable|string',
            'family_tree.*.location' => 'nullable|string',
            'family_tree.*.nationality_religion' => 'nullable|string',
            'family_tree.*.hometown' => 'nullable|string',

            // --- ADD MISSING PARENT DETAILS ---
            'father_nationality' => 'nullable|string',
            'father_religion'    => 'nullable|string',
            'father_job'         => 'nullable|string',
            'father_address'     => 'nullable|string',
            'mother_nationality' => 'nullable|string',
            'mother_religion'    => 'nullable|string',
            'mother_job'         => 'nullable|string',
            'mother_address'     => 'nullable|string',

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

            // Military Info
            'badge_no' => 'nullable|string',
            'entry_date' => 'nullable|date',
            'batch_class_no' => 'nullable|string',
            'date_comission' => 'nullable|date',
            'date_discharge' => 'nullable|date',
            'reason_discharge' => 'nullable|string',
            'units_served' => 'nullable|string',
            'disciplinary_record' => 'nullable|string',
            'pension' => 'nullable|string',

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

            //Personal Records
            'schools'             => 'nullable|string',
            'latest_school'       => 'nullable|string',
            'school_voluntary'    => 'nullable|string',
            'jobtransfer_desc'    => 'nullable|string',
            'foreign_friends_desc' => 'nullable|string',
            'relatives_officials' => 'nullable|string',
            'referal_officials'   => 'nullable|string',
            'has_criminal_rec'    => 'nullable|boolean',
            'jobs_dept'           => 'nullable|string',
            'refugee'             => 'nullable|string',

            // Abroad Table (Section 10)
            'abroads'                   => 'nullable|array',
            'abroads.*.country'         => 'required_with:abroads.*.reason|string|nullable',
            'abroads.*.reason'          => 'nullable|string',
            'abroads.*.host_name'       => 'nullable|string',
            'abroads.*.departure_date'  => 'nullable|date', 
            'abroads.*.arrival_date'    => 'nullable|date',
        ]);
    }
    public function securityIndex()
    {
        // Updated to use 'department'
        $employees = Employee::select('id', 'name', 'employee_id', 'department')->get();

        $roles = [
            'Admin' => 'Full access to all modules and BOD reports.',
            'Manager' => 'Can manage department attendance and leaves.',
            'Staff' => 'Standard access to personal records.'
        ];

        return view('employees.security', compact('employees', 'roles'));
    }
}

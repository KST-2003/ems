<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EmployeeController extends Controller
{
    public function index()
    {
        return Employee::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
            'encrypted_data' => 'nullable|string',
        ]);
        if (isset($validated['encrypted_data'])) {
            $validated['encrypted_data'] = Crypt::encryptString($validated['encrypted_data']);
        }
        return Employee::create($validated);
    }

    public function show(Employee $employee)
    {
        $employee->encrypted_data = $employee->encrypted_data ? Crypt::decryptString($employee->encrypted_data) : null;
        return $employee;
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
            'encrypted_data' => 'nullable|string',
        ]);
        if (isset($validated['encrypted_data'])) {
            $validated['encrypted_data'] = Crypt::encryptString($validated['encrypted_data']);
        }
        $employee->update($validated);
        return $employee;
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('employee_code', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('name')->paginate(15);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:security,cleaning,garbage,technical',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'join_date' => 'required|date',
            'salary_type' => 'required|in:daily,weekly,monthly',
            'base_salary' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'name', 'type', 'phone', 'email', 'address', 'id_number',
            'birth_date', 'join_date', 'salary_type', 'base_salary', 'notes'
        ]);

        $data['employee_code'] = Employee::generateEmployeeCode($request->type);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function show(Employee $employee)
    {
        $employee->load(['payrolls' => function ($q) {
            $q->orderBy('period_end', 'desc')->limit(12);
        }, 'activeDebts']);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:security,cleaning,garbage,technical',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date',
            'join_date' => 'required|date',
            'end_date' => 'nullable|date|after:join_date',
            'salary_type' => 'required|in:daily,weekly,monthly',
            'base_salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'name', 'type', 'phone', 'email', 'address', 'id_number',
            'birth_date', 'join_date', 'end_date', 'salary_type', 
            'base_salary', 'status', 'notes'
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil dihapus!');
    }
}

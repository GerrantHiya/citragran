<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('block_number', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $residents = $query->orderBy('block_number')->paginate(15);

        return view('admin.residents.index', compact('residents'));
    }

    public function create()
    {
        return view('admin.residents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'block_number' => 'required|string|max:50|unique:residents',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'move_in_date' => 'nullable|date',
            'create_account' => 'nullable|boolean',
            'password' => 'nullable|required_if:create_account,1|min:8',
        ]);

        $resident = Resident::create($request->only([
            'name', 'block_number', 'phone', 'whatsapp', 'email', 'address', 'move_in_date'
        ]));

        // Create user account if requested
        if ($request->create_account && $request->email) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'resident',
                'phone' => $request->phone,
            ]);

            $resident->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.residents.index')
            ->with('success', 'Data warga berhasil ditambahkan!');
    }

    public function show(Resident $resident)
    {
        $resident->load(['user', 'iplBills' => function ($q) {
            $q->orderBy('year', 'desc')->orderBy('month', 'desc');
        }, 'reports' => function ($q) {
            $q->latest();
        }]);

        return view('admin.residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        return view('admin.residents.edit', compact('resident'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'block_number' => 'required|string|max:50|unique:residents,block_number,' . $resident->id,
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'move_in_date' => 'nullable|date',
        ]);

        $resident->update($request->only([
            'name', 'block_number', 'phone', 'whatsapp', 'email', 'address', 'status', 'move_in_date'
        ]));

        // Update linked user if exists
        if ($resident->user) {
            $resident->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }

        return redirect()->route('admin.residents.index')
            ->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return redirect()->route('admin.residents.index')
            ->with('success', 'Data warga berhasil dihapus!');
    }
}

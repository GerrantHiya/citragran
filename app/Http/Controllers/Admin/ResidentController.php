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

        // Get unlinked users (users with role 'resident' but no resident data linked)
        $unlinkedUsers = User::where('role', 'resident')
            ->whereDoesntHave('resident')
            ->get();

        return view('admin.residents.index', compact('residents', 'unlinkedUsers'));
    }

    public function create()
    {
        // Get unlinked users for linking option
        $unlinkedUsers = User::where('role', 'resident')
            ->whereDoesntHave('resident')
            ->get();

        return view('admin.residents.create', compact('unlinkedUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'block_number' => 'required|string|max:50|unique:residents',
            'land_area' => 'required|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'move_in_date' => 'nullable|date',
            'link_user_id' => 'nullable|exists:users,id',
            'create_account' => 'nullable|boolean',
            'password' => 'nullable|required_if:create_account,1|min:8',
        ]);

        $resident = Resident::create($request->only([
            'name', 'block_number', 'land_area', 'phone', 'whatsapp', 'email', 'address', 'move_in_date'
        ]));

        // Link existing user account if selected
        if ($request->link_user_id) {
            $resident->update(['user_id' => $request->link_user_id]);
            return redirect()->route('admin.residents.index')
                ->with('success', 'Data warga berhasil ditambahkan dan dihubungkan dengan akun yang ada!');
        }

        // Create new user account if requested
        if ($request->create_account && $request->email) {
            // Check if email already exists
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return redirect()->route('admin.residents.index')
                    ->with('success', 'Data warga berhasil ditambahkan!')
                    ->with('error', 'Namun akun tidak dapat dibuat karena email sudah terdaftar.');
            }

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

        // Get unlinked users for linking option
        $unlinkedUsers = User::where('role', 'resident')
            ->whereDoesntHave('resident')
            ->get();

        return view('admin.residents.show', compact('resident', 'unlinkedUsers'));
    }

    public function edit(Resident $resident)
    {
        // Get unlinked users for linking option
        $unlinkedUsers = User::where('role', 'resident')
            ->whereDoesntHave('resident')
            ->get();

        return view('admin.residents.edit', compact('resident', 'unlinkedUsers'));
    }

    public function update(Request $request, Resident $resident)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'block_number' => 'required|string|max:50|unique:residents,block_number,' . $resident->id,
            'land_area' => 'required|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'move_in_date' => 'nullable|date',
        ]);

        $resident->update($request->only([
            'name', 'block_number', 'land_area', 'phone', 'whatsapp', 'email', 'address', 'status', 'move_in_date'
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
        // Unlink user account before deleting (don't delete the user)
        if ($resident->user_id) {
            $resident->update(['user_id' => null]);
        }

        // Force delete the block_number to allow reuse
        $resident->forceDelete();

        return redirect()->route('admin.residents.index')
            ->with('success', 'Data warga berhasil dihapus!');
    }

    /**
     * Link an existing user account to a resident
     */
    public function linkUser(Request $request, Resident $resident)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if user is already linked to another resident
        $existingLink = Resident::where('user_id', $request->user_id)->first();
        if ($existingLink) {
            return back()->with('error', 'Akun ini sudah terhubung dengan warga lain!');
        }

        $resident->update(['user_id' => $request->user_id]);

        return back()->with('success', 'Akun berhasil dihubungkan dengan data warga!');
    }

    /**
     * Unlink user account from resident
     */
    public function unlinkUser(Resident $resident)
    {
        $resident->update(['user_id' => null]);

        return back()->with('success', 'Akun berhasil diputus dari data warga!');
    }
}

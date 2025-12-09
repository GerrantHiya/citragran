<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\IplBill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $resident = auth()->user()->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard');
        }

        $query = $resident->iplBills();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $bills = $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        $years = $resident->iplBills()->distinct()->pluck('year');

        return view('resident.bills.index', compact('bills', 'years'));
    }

    public function show(IplBill $bill)
    {
        $resident = auth()->user()->resident;

        if (!$resident || $bill->resident_id !== $resident->id) {
            abort(403);
        }

        $bill->load(['items.billingType', 'payments']);

        return view('resident.bills.show', compact('bill'));
    }

    public function history()
    {
        $resident = auth()->user()->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard');
        }

        $bills = $resident->iplBills()
            ->where('status', 'paid')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('resident.bills.history', compact('bills'));
    }
}

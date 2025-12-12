<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingType;
use App\Models\IplBill;
use App\Models\IplBillItem;
use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Http\Request;

class IplBillController extends Controller
{
    public function index(Request $request)
    {
        $query = IplBill::with('resident');

        if ($request->search) {
            $query->whereHas('resident', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('block_number', 'like', "%{$request->search}%");
            })->orWhere('bill_number', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->month) {
            $query->where('month', $request->month);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        $bills = $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.ipl-bills.index', compact('bills'));
    }

    public function create()
    {
        $residents = Resident::where('status', 'active')->orderBy('block_number')->get();
        $billingTypes = BillingType::active()->get();

        return view('admin.ipl-bills.create', compact('residents', 'billingTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'due_date' => 'required|date',
            'items' => 'required|array',
            'items.*.billing_type_id' => 'required|exists:billing_types,id',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        // Check if bill already exists for this period
        $exists = IplBill::where('resident_id', $request->resident_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return back()->withErrors(['period' => 'Tagihan untuk periode ini sudah ada!']);
        }

        $totalAmount = collect($request->items)->sum('amount');

        $bill = IplBill::create([
            'resident_id' => $request->resident_id,
            'bill_number' => IplBill::generateBillNumber($request->year, $request->month),
            'month' => $request->month,
            'year' => $request->year,
            'total_amount' => $totalAmount,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            IplBillItem::create([
                'ipl_bill_id' => $bill->id,
                'billing_type_id' => $item['billing_type_id'],
                'amount' => $item['amount'],
                'meter_previous' => $item['meter_previous'] ?? null,
                'meter_current' => $item['meter_current'] ?? null,
                'usage' => isset($item['meter_previous'], $item['meter_current']) 
                    ? $item['meter_current'] - $item['meter_previous'] 
                    : null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('admin.ipl-bills.index')
            ->with('success', 'Tagihan IPL berhasil dibuat!');
    }

    public function show(IplBill $iplBill)
    {
        $iplBill->load(['resident', 'items.billingType', 'payments']);

        return view('admin.ipl-bills.show', compact('iplBill'));
    }

    public function edit(IplBill $iplBill)
    {
        if ($iplBill->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat diedit!');
        }

        $iplBill->load('items');
        $residents = Resident::where('status', 'active')->orderBy('block_number')->get();
        $billingTypes = BillingType::active()->get();

        return view('admin.ipl-bills.edit', compact('iplBill', 'residents', 'billingTypes'));
    }

    public function update(Request $request, IplBill $iplBill)
    {
        if ($iplBill->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat diedit!');
        }

        $request->validate([
            'due_date' => 'required|date',
            'items' => 'required|array',
            'items.*.billing_type_id' => 'required|exists:billing_types,id',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        $totalAmount = collect($request->items)->sum('amount');

        $iplBill->update([
            'total_amount' => $totalAmount,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        // Delete existing items and recreate
        $iplBill->items()->delete();

        foreach ($request->items as $item) {
            IplBillItem::create([
                'ipl_bill_id' => $iplBill->id,
                'billing_type_id' => $item['billing_type_id'],
                'amount' => $item['amount'],
                'meter_previous' => $item['meter_previous'] ?? null,
                'meter_current' => $item['meter_current'] ?? null,
                'usage' => isset($item['meter_previous'], $item['meter_current']) 
                    ? $item['meter_current'] - $item['meter_previous'] 
                    : null,
                'notes' => $item['notes'] ?? null,
            ]);
        }

        $iplBill->updateStatus();

        return redirect()->route('admin.ipl-bills.index')
            ->with('success', 'Tagihan IPL berhasil diperbarui!');
    }

    public function destroy(IplBill $iplBill)
    {
        if ($iplBill->paid_amount > 0) {
            return back()->with('error', 'Tagihan yang sudah ada pembayaran tidak dapat dihapus!');
        }

        $iplBill->delete();

        return redirect()->route('admin.ipl-bills.index')
            ->with('success', 'Tagihan IPL berhasil dihapus!');
    }

    public function addPayment(Request $request, IplBill $iplBill)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $iplBill->remaining_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,qris',
            'reference_number' => 'nullable|string|max:100',
        ]);

        Payment::create([
            'ipl_bill_id' => $iplBill->id,
            'payment_number' => Payment::generatePaymentNumber(),
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'received_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.ipl-bills.show', $iplBill)
            ->with('success', 'Pembayaran berhasil dicatat!');
    }

    public function generateBulk()
    {
        $residents = Resident::where('status', 'active')->orderBy('block_number')->get();
        $rtFee = \App\Models\RtFee::getActiveFee();

        return view('admin.ipl-bills.generate-bulk', compact('residents', 'rtFee'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'due_date' => 'required|date',
        ]);

        $residents = Resident::where('status', 'active')->get();
        $rtFee = \App\Models\RtFee::getActiveFee();
        $rtAmount = $rtFee ? $rtFee->amount : 0;
        
        $created = 0;
        $skipped = 0;

        foreach ($residents as $resident) {
            // Check if bill already exists
            $exists = IplBill::where('resident_id', $resident->id)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Get IPL amount based on land area
            $iplAmount = $resident->ipl_amount;
            $totalAmount = $iplAmount + $rtAmount;

            // Skip if no land area defined and no IPL rate
            if ($totalAmount <= 0) {
                $skipped++;
                continue;
            }

            $bill = IplBill::create([
                'resident_id' => $resident->id,
                'bill_number' => IplBill::generateBillNumber($request->year, $request->month),
                'month' => $request->month,
                'year' => $request->year,
                'total_amount' => $totalAmount,
                'due_date' => $request->due_date,
            ]);

            // Get or create billing type for IPL
            $iplBillingType = BillingType::firstOrCreate(
                ['code' => 'ipl'],
                ['name' => 'IPL (Iuran Pengelolaan Lingkungan)', 'default_amount' => 0]
            );

            // Get or create billing type for Iuran RT
            $rtBillingType = BillingType::firstOrCreate(
                ['code' => 'rt'],
                ['name' => 'Iuran RT', 'default_amount' => $rtAmount]
            );

            // Create IPL bill item
            if ($iplAmount > 0) {
                IplBillItem::create([
                    'ipl_bill_id' => $bill->id,
                    'billing_type_id' => $iplBillingType->id,
                    'amount' => $iplAmount,
                    'notes' => 'Luas tanah: ' . ($resident->land_area ?? 0) . ' m²',
                ]);
            }

            // Create Iuran RT bill item
            if ($rtAmount > 0) {
                IplBillItem::create([
                    'ipl_bill_id' => $bill->id,
                    'billing_type_id' => $rtBillingType->id,
                    'amount' => $rtAmount,
                ]);
            }

            $created++;
        }

        return redirect()->route('admin.ipl-bills.index')
            ->with('success', "Berhasil membuat {$created} tagihan. {$skipped} tagihan dilewati (sudah ada/tidak memenuhi syarat).");
    }
}

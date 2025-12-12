<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RtFee;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function billingSettings()
    {
        $rtFee = RtFee::getActiveFee();
        
        return view('admin.settings.billing', compact('rtFee'));
    }

    public function updateRtFee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $rtFee = RtFee::getActiveFee();
        
        if ($rtFee) {
            $rtFee->update([
                'name' => $request->name,
                'amount' => $request->amount,
                'description' => $request->description,
            ]);
        } else {
            RtFee::create([
                'name' => $request->name,
                'amount' => $request->amount,
                'description' => $request->description,
                'is_active' => true,
            ]);
        }

        return back()->with('success', 'Pengaturan iuran RT berhasil disimpan!');
    }
}

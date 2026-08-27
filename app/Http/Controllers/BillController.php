<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Patient;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::with(['patient', 'appointment'])->latest()->paginate(10);
        $patients = Patient::all();
        $appointments = Appointment::all();

        return view('bills.index', compact('bills', 'patients', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'consultation_fee' => 'required|numeric|min:0',
            'room_charge' => 'required|numeric|min:0',
            'medicine_charge' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|in:Unpaid,Paid,Partially Paid',
        ]);

        $subtotal = $validated['consultation_fee'] + $validated['room_charge'] + $validated['medicine_charge'];
        $tax = $subtotal * 0.05; // 5% tax
        $validated['tax'] = $tax;
        $validated['total_amount'] = $subtotal + $tax;
        $validated['invoice_number'] = 'INV-' . rand(100000, 999999);
        $validated['bill_date'] = now();

        Bill::create($validated);

        return redirect()->route('bills.index')->with('success', 'Invoice created successfully!');
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $request->validate([
            'status' => 'required|in:Unpaid,Paid,Partially Paid',
        ]);

        $bill->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Bill status updated!');
    }
}

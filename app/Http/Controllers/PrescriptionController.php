<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['patient', 'doctor', 'appointment'])->latest()->paginate(10);
        $patients = Patient::all();
        $doctors = Doctor::all();
        $appointments = Appointment::where('status', 'Confirmed')->orWhere('status', 'Scheduled')->get();

        return view('prescriptions.index', compact('prescriptions', 'patients', 'doctors', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'medicines' => 'required|string',
            'dosage_instructions' => 'nullable|string',
            'lab_tests' => 'nullable|string',
            'prescription_date' => 'required|date',
        ]);

        Prescription::create($validated);

        if ($request->filled('appointment_id')) {
            Appointment::where('id', $request->appointment_id)->update(['status' => 'Completed']);
        }

        return redirect()->route('prescriptions.index')->with('success', 'Prescription created successfully!');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success', 'Prescription deleted!');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrescriptionApiController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Prescription::with(['patient', 'doctor', 'appointment']);

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $prescriptions = $query->latest()->get();

        return $this->successResponse($prescriptions, 'Prescriptions retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'medicines' => 'required|string',
            'dosage_instructions' => 'nullable|string',
            'lab_tests' => 'nullable|string',
            'prescription_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $prescription = Prescription::create($validator->validated());

        if ($request->filled('appointment_id')) {
            Appointment::where('id', $request->appointment_id)->update(['status' => 'Completed']);
        }

        $prescription->load(['patient', 'doctor', 'appointment']);

        return $this->successResponse($prescription, 'Prescription created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $prescription = Prescription::with(['patient', 'doctor', 'appointment'])->find($id);

        if (!$prescription) {
            return $this->errorResponse('Prescription not found', 404);
        }

        return $this->successResponse($prescription, 'Prescription details retrieved');
    }

    public function destroy($id): JsonResponse
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return $this->errorResponse('Prescription not found', 404);
        }

        $prescription->delete();

        return $this->successResponse(null, 'Prescription deleted');
    }
}

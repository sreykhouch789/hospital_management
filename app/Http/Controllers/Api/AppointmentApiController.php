<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentApiController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $appointments = $query->latest()->get();

        return $this->successResponse($appointments, 'Appointments list retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $data = $validator->validated();
        $data['appointment_number'] = 'APT-' . strtoupper(substr(uniqid(), 7));
        $data['status'] = 'Scheduled';

        $appointment = Appointment::create($data);
        $appointment->load(['patient', 'doctor']);

        return $this->successResponse($appointment, 'Appointment scheduled successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $appointment = Appointment::with(['patient', 'doctor', 'prescription', 'bill'])->find($id);

        if (!$appointment) {
            return $this->errorResponse('Appointment not found', 404);
        }

        return $this->successResponse($appointment, 'Appointment details retrieved');
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return $this->errorResponse('Appointment not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Scheduled,Confirmed,Completed,Cancelled',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $appointment->update(['status' => $request->status]);

        return $this->successResponse($appointment, 'Appointment status updated to ' . $request->status);
    }

    public function destroy($id): JsonResponse
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return $this->errorResponse('Appointment not found', 404);
        }

        $appointment->delete();

        return $this->successResponse(null, 'Appointment deleted');
    }
}

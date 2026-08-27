<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorApiController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Doctor::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $doctors = $query->latest()->get();

        return $this->successResponse($doctors, 'Doctors list retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20',
            'consultation_fee' => 'required|numeric|min:0',
            'available_days' => 'required|string',
            'available_time' => 'required|string',
            'status' => 'nullable|in:active,on_leave,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $doctor = Doctor::create($validator->validated());
        $doctor->load('department');

        return $this->successResponse($doctor, 'Doctor created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $doctor = Doctor::with(['department', 'appointments', 'prescriptions'])->find($id);

        if (!$doctor) {
            return $this->errorResponse('Doctor not found', 404);
        }

        return $this->successResponse($doctor, 'Doctor details retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return $this->errorResponse('Doctor not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'department_id' => 'sometimes|exists:departments,id',
            'specialization' => 'sometimes|string|max:255',
            'email' => "sometimes|email|unique:doctors,email,{$id}",
            'phone' => 'sometimes|string|max:20',
            'consultation_fee' => 'sometimes|numeric|min:0',
            'available_days' => 'sometimes|string',
            'available_time' => 'sometimes|string',
            'status' => 'sometimes|in:active,on_leave,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $doctor->update($validator->validated());
        $doctor->load('department');

        return $this->successResponse($doctor, 'Doctor updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return $this->errorResponse('Doctor not found', 404);
        }

        $doctor->delete();
        return $this->successResponse(null, 'Doctor deleted successfully');
    }
}

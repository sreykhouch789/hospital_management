<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientApiController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Patient::with(['appointments', 'prescriptions']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('mrn', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $patients = $query->latest()->get();

        return $this->successResponse($patients, 'Patients list retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $data = $validator->validated();
        $data['mrn'] = 'PAT-' . rand(10000, 99999);

        $patient = Patient::create($data);

        return $this->successResponse($patient, 'Patient registered successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $patient = Patient::with(['appointments.doctor', 'prescriptions', 'bills', 'room'])->find($id);

        if (!$patient) {
            return $this->errorResponse('Patient not found', 404);
        }

        return $this->successResponse($patient, 'Patient record details retrieved');
    }

    public function update(Request $request, $id): JsonResponse
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return $this->errorResponse('Patient not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:0|max:120',
            'gender' => 'sometimes|in:Male,Female,Other',
            'blood_group' => 'sometimes|string|max:10',
            'phone' => 'sometimes|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $patient->update($validator->validated());

        return $this->successResponse($patient, 'Patient details updated');
    }

    public function destroy($id): JsonResponse
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return $this->errorResponse('Patient not found', 404);
        }

        $patient->delete();

        return $this->successResponse(null, 'Patient record deleted');
    }
}

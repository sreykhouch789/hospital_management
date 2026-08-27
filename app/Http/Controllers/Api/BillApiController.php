<?php

namespace App\Http\Controllers\Api;

use App\Models\Bill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillApiController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Bill::with(['patient', 'appointment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        $bills = $query->latest()->get();

        return $this->successResponse($bills, 'Bills list retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'consultation_fee' => 'required|numeric|min:0',
            'room_charge' => 'required|numeric|min:0',
            'medicine_charge' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'status' => 'required|in:Unpaid,Paid,Partially Paid',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $data = $validator->validated();
        $subtotal = $data['consultation_fee'] + $data['room_charge'] + $data['medicine_charge'];
        $tax = $subtotal * 0.05;
        $data['tax'] = $tax;
        $data['total_amount'] = $subtotal + $tax;
        $data['invoice_number'] = 'INV-' . rand(100000, 999999);
        $data['bill_date'] = now();

        $bill = Bill::create($data);
        $bill->load(['patient', 'appointment']);

        return $this->successResponse($bill, 'Invoice created successfully', 201);
    }

    public function show($id): JsonResponse
    {
        $bill = Bill::with(['patient', 'appointment'])->find($id);

        if (!$bill) {
            return $this->errorResponse('Bill invoice not found', 404);
        }

        return $this->successResponse($bill, 'Bill invoice details retrieved');
    }

    public function updateStatus(Request $request, $id): JsonResponse
    {
        $bill = Bill::find($id);

        if (!$bill) {
            return $this->errorResponse('Bill invoice not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Unpaid,Paid,Partially Paid',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $bill->update(['status' => $request->status]);

        return $this->successResponse($bill, 'Invoice payment status updated to ' . $request->status);
    }
}

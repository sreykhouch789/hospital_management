<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomApiController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Room::with('patient');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $rooms = $query->orderBy('room_number')->get();

        return $this->successResponse($rooms, 'Rooms list retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|unique:rooms,room_number',
            'type' => 'required|in:General Ward,Private Room,Semi-Private,ICU,Emergency',
            'daily_rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $data = $validator->validated();
        $data['status'] = 'Available';

        $room = Room::create($data);

        return $this->successResponse($room, 'Room added successfully', 201);
    }

    public function assignPatient(Request $request, $id): JsonResponse
    {
        $room = Room::find($id);

        if (!$room) {
            return $this->errorResponse('Room not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $room->update([
            'patient_id' => $request->patient_id,
            'status' => 'Occupied',
        ]);

        $room->load('patient');

        return $this->successResponse($room, "Patient assigned to Room {$room->room_number}");
    }

    public function dischargePatient($id): JsonResponse
    {
        $room = Room::find($id);

        if (!$room) {
            return $this->errorResponse('Room not found', 404);
        }

        $room->update([
            'patient_id' => null,
            'status' => 'Available',
        ]);

        return $this->successResponse($room, "Room {$room->room_number} discharged and available");
    }
}

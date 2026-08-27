<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('patient')->orderBy('room_number')->paginate(12);
        $patients = Patient::all();

        return view('rooms.index', compact('rooms', 'patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number',
            'type' => 'required|in:General Ward,Private Room,Semi-Private,ICU,Emergency',
            'daily_rate' => 'required|numeric|min:0',
        ]);

        $validated['status'] = 'Available';

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Room added successfully!');
    }

    public function assignPatient(Request $request, Room $room)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $room->update([
            'patient_id' => $validated['patient_id'],
            'status' => 'Occupied',
        ]);

        return redirect()->route('rooms.index')->with('success', "Patient assigned to Room {$room->room_number}!");
    }

    public function dischargePatient(Room $room)
    {
        $room->update([
            'patient_id' => null,
            'status' => 'Available',
        ]);

        return redirect()->route('rooms.index')->with('success', "Room {$room->room_number} has been discharged and is now available!");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'available_rooms' => Room::where('status', 'Available')->count(),
            'total_rooms' => Room::count(),
            'total_revenue' => Bill::where('status', 'Paid')->sum('total_amount'),
            'pending_bills' => Bill::where('status', 'Unpaid')->count(),
        ];

        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(6)
            ->get();

        $doctors = Doctor::with('department')->take(4)->get();
        $patients = Patient::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentAppointments', 'doctors', 'patients'));
    }
}

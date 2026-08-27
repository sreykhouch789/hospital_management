<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class StatsApiController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'available_rooms' => Room::where('status', 'Available')->count(),
            'total_rooms' => Room::count(),
            'total_revenue' => (float) Bill::where('status', 'Paid')->sum('total_amount'),
            'unpaid_bills' => Bill::where('status', 'Unpaid')->count(),
        ];

        return $this->successResponse($stats, 'Hospital KPI statistics retrieved');
    }
}

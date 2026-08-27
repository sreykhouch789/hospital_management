@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-emerald-400"></i> Appointments Management
        </h1>
        <p class="text-xs text-slate-400">Schedule consultations, view doctor availability, and update status.</p>
    </div>
    
    <button onclick="document.getElementById('addAppointmentModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-calendar-plus"></i> Book Appointment
    </button>
</div>

<!-- Appointments Table -->
<div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-400 uppercase bg-slate-900/90 border-b border-slate-800">
                <tr>
                    <th class="py-3.5 px-4">APT Number</th>
                    <th class="py-3.5 px-4">Patient</th>
                    <th class="py-3.5 px-4">Doctor & Dept</th>
                    <th class="py-3.5 px-4">Date & Time</th>
                    <th class="py-3.5 px-4">Symptoms / Reason</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($appointments as $apt)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-emerald-400">{{ $apt->appointment_number }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-white">{{ $apt->patient->name ?? 'N/A' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $apt->patient->phone ?? '' }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-200">{{ $apt->doctor->name ?? 'N/A' }}</div>
                            <div class="text-[11px] text-emerald-400">{{ $apt->doctor->specialization ?? '' }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-300 font-medium">
                            {{ $apt->appointment_date->format('M d, Y - h:i A') }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-400 max-w-xs truncate">
                            {{ $apt->symptoms ?? 'General Checkup' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <form action="{{ route('appointments.updateStatus', $apt->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-slate-900 border text-[11px] font-semibold rounded px-2 py-1 focus:outline-none 
                                    @if($apt->status === 'Confirmed') border-emerald-500/40 text-emerald-400
                                    @elseif($apt->status === 'Scheduled') border-amber-500/40 text-amber-400
                                    @elseif($apt->status === 'Completed') border-blue-500/40 text-blue-400
                                    @else border-rose-500/40 text-rose-400 @endif">
                                    <option value="Scheduled" {{ $apt->status === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="Confirmed" {{ $apt->status === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="Completed" {{ $apt->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Cancelled" {{ $apt->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <form action="{{ route('appointments.destroy', $apt->id) }}" method="POST" onsubmit="return confirm('Cancel & remove appointment?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-500 hover:text-rose-400 text-xs p-1">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-500">No appointments recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $appointments->links() }}
</div>

<!-- Modal: Book Appointment -->
<div id="addAppointmentModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus text-emerald-400"></i> Book Appointment
            </h3>
            <button onclick="document.getElementById('addAppointmentModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('appointments.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-medium mb-1">Select Patient</label>
                <select name="patient_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    <option value="">-- Choose Patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Select Doctor</label>
                <select name="doctor_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    <option value="">-- Choose Doctor --</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }} ({{ $doctor->specialization }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Appointment Date & Time</label>
                <input type="datetime-local" name="appointment_date" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Symptoms / Notes</label>
                <textarea name="symptoms" rows="2" placeholder="Describe symptoms or reason for visit..." class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addAppointmentModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">Schedule Appointment</button>
            </div>
        </form>
    </div>
</div>
@endsection

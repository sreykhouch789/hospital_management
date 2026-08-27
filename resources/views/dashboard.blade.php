@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<!-- Header Greeting -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-slate-800 to-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl">
    <div>
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            Welcome to MediPulse Control Center <span class="inline-block animate-bounce">👋</span>
        </h1>
        <p class="text-slate-400 text-sm mt-1">Here is what's happening across hospital departments today.</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1.5 rounded-lg font-medium flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Live Hospital Status
        </span>
    </div>
</div>

<!-- Stat Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <!-- Card 1: Total Patients -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-emerald-500/50 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Patients</p>
                <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($stats['total_patients']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-procedures"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-400 gap-1">
            <span class="text-emerald-400 font-semibold flex items-center"><i class="fa-solid fa-arrow-up mr-1"></i> Active</span>
            <span>in patient database</span>
        </div>
    </div>

    <!-- Card 2: Active Doctors -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-teal-500/50 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Doctors</p>
                <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($stats['total_doctors']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-400 gap-1">
            <span class="text-teal-400 font-semibold">Across 5 departments</span>
        </div>
    </div>

    <!-- Card 3: Today's Appointments -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-cyan-500/50 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Today's Appointments</p>
                <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($stats['today_appointments']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-400 gap-1">
            <span class="text-cyan-400 font-semibold">Scheduled & Confirmed</span>
        </div>
    </div>

    <!-- Card 4: Bed Availability -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-amber-500/50 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Available Rooms</p>
                <h3 class="text-3xl font-extrabold text-white mt-1">{{ $stats['available_rooms'] }} / {{ $stats['total_rooms'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bed"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-400 gap-1">
            <span class="text-amber-400 font-semibold">Ready for admission</span>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Recent Appointments Table (2 Cols) -->
    <div class="lg:col-span-2 glass-panel p-6 rounded-2xl border border-slate-800">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-400"></i> Recent Appointments
                </h3>
                <p class="text-xs text-slate-400">Latest scheduled patient consultations</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
                View All <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase bg-slate-900/80 border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4 rounded-l-lg">APT #</th>
                        <th class="py-3 px-4">Patient</th>
                        <th class="py-3 px-4">Doctor</th>
                        <th class="py-3 px-4">Date & Time</th>
                        <th class="py-3 px-4 rounded-r-lg">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($recentAppointments as $apt)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4 font-mono font-semibold text-emerald-400">{{ $apt->appointment_number }}</td>
                            <td class="py-3 px-4 font-medium text-white">{{ $apt->patient->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-slate-300">{{ $apt->doctor->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-slate-400">{{ $apt->appointment_date->format('M d, Y - h:i A') }}</td>
                            <td class="py-3 px-4">
                                @if($apt->status === 'Confirmed')
                                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded text-[11px] font-medium">Confirmed</span>
                                @elseif($apt->status === 'Scheduled')
                                    <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded text-[11px] font-medium">Scheduled</span>
                                @elseif($apt->status === 'Completed')
                                    <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-0.5 rounded text-[11px] font-medium">Completed</span>
                                @else
                                    <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded text-[11px] font-medium">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-500">No appointments scheduled yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- On Duty Doctors Sidebar -->
    <div class="glass-panel p-6 rounded-2xl border border-slate-800 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-doctor text-emerald-400"></i> Featured Doctors
                </h3>
                <a href="{{ route('doctors.index') }}" class="text-xs text-emerald-400 hover:underline">Manage</a>
            </div>

            <div class="space-y-4">
                @foreach($doctors as $doctor)
                    <div class="p-3 rounded-xl bg-slate-800/60 border border-slate-700/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center font-bold text-emerald-400 text-sm">
                                {{ strtoupper(substr($doctor->name, 4, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white">{{ $doctor->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $doctor->specialization }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] bg-slate-900 text-emerald-400 font-mono px-2 py-1 rounded border border-slate-700">
                            ${{ number_format($doctor->consultation_fee, 0) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-800">
            <div class="bg-gradient-to-r from-emerald-950 to-teal-950 p-4 rounded-xl border border-emerald-500/30">
                <div class="flex items-center gap-2 text-emerald-400 text-xs font-semibold">
                    <i class="fa-solid fa-headset text-sm"></i> Emergency Desk Active
                </div>
                <p class="text-[11px] text-slate-300 mt-1">24/7 Hotline: +1 (800) 555-MEDI</p>
            </div>
        </div>
    </div>
</div>
@endsection

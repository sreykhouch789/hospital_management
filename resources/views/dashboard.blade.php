@extends('layouts.app')

@section('title', __('app.dashboard_title'))

@section('content')
<!-- Header Greeting -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-emerald-600 to-teal-700 p-6 rounded-2xl text-white shadow-lg shadow-emerald-600/10">
    <div>
        <h1 class="text-2xl font-bold flex items-center gap-2">
            {{ __('app.welcome_title') }} <span class="inline-block animate-bounce">👋</span>
        </h1>
        <p class="text-emerald-100 text-sm mt-1">{{ __('app.welcome_subtitle') }}</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-xs bg-white/20 backdrop-blur-md text-white border border-white/30 px-3 py-1.5 rounded-lg font-medium flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span> {{ __('app.live_status') }}
        </span>
    </div>
</div>

<!-- Stat Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <!-- Card 1: Total Patients -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-emerald-400 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('app.total_patients') }}</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_patients']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-procedures"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-500 gap-1">
            <span class="text-emerald-600 font-semibold flex items-center"><i class="fa-solid fa-arrow-up mr-1"></i> {{ __('app.active_in_database') }}</span>
        </div>
    </div>

    <!-- Card 2: Active Doctors -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-teal-400 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('app.active_doctors') }}</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['total_doctors']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-500 gap-1">
            <span class="text-teal-600 font-semibold">{{ __('app.across_departments') }}</span>
        </div>
    </div>

    <!-- Card 3: Today's Appointments -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-cyan-400 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('app.today_appointments') }}</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['today_appointments']) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-50 border border-cyan-100 flex items-center justify-center text-cyan-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-500 gap-1">
            <span class="text-cyan-600 font-semibold">{{ __('app.scheduled_confirmed') }}</span>
        </div>
    </div>

    <!-- Card 4: Bed Availability -->
    <div class="glass-panel p-5 rounded-2xl relative overflow-hidden group hover:border-amber-400 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('app.available_rooms') }}</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['available_rooms'] }} / {{ $stats['total_rooms'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bed"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-slate-500 gap-1">
            <span class="text-amber-600 font-semibold">{{ __('app.ready_for_admission') }}</span>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Recent Appointments Table (2 Cols) -->
    <div class="lg:col-span-2 glass-panel p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> {{ __('app.recent_appointments') }}
                </h3>
                <p class="text-xs text-slate-500">{{ __('app.recent_appointments_sub') }}</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                {{ __('app.view_all') }} <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 rounded-l-lg">{{ __('app.apt_no') }}</th>
                        <th class="py-3 px-4">{{ __('app.patient') }}</th>
                        <th class="py-3 px-4">{{ __('app.doctor') }}</th>
                        <th class="py-3 px-4">{{ __('app.date_time') }}</th>
                        <th class="py-3 px-4 rounded-r-lg">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentAppointments as $apt)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4 font-mono font-bold text-emerald-600">{{ $apt->appointment_number }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $apt->patient->name ?? __('app.na') }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $apt->doctor->name ?? __('app.na') }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $apt->appointment_date->format('M d, Y - h:i A') }}</td>
                            <td class="py-3 px-4">
                                @if($apt->status === 'Confirmed')
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[11px] font-medium">{{ __('app.status_confirmed') }}</span>
                                @elseif($apt->status === 'Scheduled')
                                    <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full text-[11px] font-medium">{{ __('app.status_scheduled') }}</span>
                                @elseif($apt->status === 'Completed')
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full text-[11px] font-medium">{{ __('app.status_completed') }}</span>
                                @else
                                    <span class="bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full text-[11px] font-medium">{{ __('app.status_cancelled') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-slate-400">{{ __('app.no_recent_appointments') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- On Duty Doctors Sidebar -->
    <div class="glass-panel p-6 rounded-2xl flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-doctor text-emerald-600"></i> {{ __('app.featured_doctors') }}
                </h3>
                <a href="{{ route('doctors.index') }}" class="text-xs text-emerald-600 hover:underline font-medium">{{ __('app.manage') }}</a>
            </div>

            <div class="space-y-3">
                @foreach($doctors as $doctor)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center font-bold text-emerald-700 text-sm">
                                {{ strtoupper(substr($doctor->name, 4, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">{{ $doctor->name }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $doctor->specialization }}</p>
                            </div>
                        </div>
                        <span class="text-[11px] bg-white text-emerald-700 font-mono px-2 py-0.5 rounded border border-slate-200 font-bold">
                            ${{ number_format($doctor->consultation_fee, 0) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100">
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-4 rounded-xl border border-emerald-200">
                <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold">
                    <i class="fa-solid fa-headset text-sm text-emerald-600"></i> {{ __('app.emergency_desk') }}
                </div>
                <p class="text-[11px] text-slate-600 mt-1 font-medium">{{ __('app.hotline') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection



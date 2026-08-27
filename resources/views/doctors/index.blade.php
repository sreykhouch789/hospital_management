@extends('layouts.app')

@section('title', __('app.doctors_title'))

@section('content')
<!-- Top Header Action & Filter -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-user-doctor text-emerald-400"></i> {{ __('app.doctors_title') }}
        </h1>
        <p class="text-xs text-slate-400">{{ __('app.doctors_subtitle') }}</p>
    </div>
    
    <button onclick="document.getElementById('addDoctorModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> {{ __('app.add_doctor') }}
    </button>
</div>

<!-- Search & Filter Form -->
<form method="GET" action="{{ route('doctors.index') }}" class="glass-panel p-4 rounded-xl border border-slate-800 flex flex-col sm:flex-row gap-4">
    <div class="flex-1 relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_placeholder') }}" class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-200 rounded-lg pl-8 pr-4 py-2 focus:outline-none focus:border-emerald-500">
    </div>
    
    <div class="w-full sm:w-48">
        <select name="department_id" onchange="this.form.submit()" class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            <option value="">{{ __('app.department') }}</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
</form>

<!-- Doctors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($doctors as $doctor)
        <div class="glass-panel p-5 rounded-2xl border border-slate-800 flex flex-col justify-between hover:border-emerald-500/40 transition-all group relative">
            <div>
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-bold text-slate-950 text-lg shadow-md">
                            {{ strtoupper(substr($doctor->name, 4, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">{{ $doctor->name }}</h3>
                            <span class="text-[11px] bg-slate-800 text-emerald-300 px-2 py-0.5 rounded border border-slate-700 font-medium">
                                {{ $doctor->specialization }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-500 hover:text-rose-400 text-xs p-1">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-800 space-y-2 text-xs text-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-building text-emerald-400 w-4"></i> {{ __('app.department') }}:</span>
                        <span class="font-medium text-white">{{ $doctor->department->name ?? __('app.na') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-phone text-emerald-400 w-4"></i> {{ __('app.phone') }}:</span>
                        <span class="font-mono text-slate-300">{{ $doctor->phone }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-clock text-emerald-400 w-4"></i> Schedule:</span>
                        <span class="font-medium text-slate-200">{{ $doctor->available_days }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-dollar-sign text-emerald-400 w-4"></i> {{ __('app.consultation_fee') }}:</span>
                        <span class="font-mono text-emerald-400 font-bold">${{ number_format($doctor->consultation_fee, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <span class="text-[10px] text-slate-400">{{ $doctor->available_time }}</span>
                <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded font-medium border border-emerald-500/20">
                    {{ ucfirst($doctor->status) }}
                </span>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-slate-500 glass-panel rounded-2xl">
            <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
            <p>{{ __('app.no_doctors') }}</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $doctors->links() }}
</div>

<!-- Modal: Add New Doctor -->
<div id="addDoctorModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-emerald-400"></i> {{ __('app.add_doctor') }}
            </h3>
            <button onclick="document.getElementById('addDoctorModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('doctors.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-medium mb-1">{{ __('app.doctor_name') }}</label>
                <input type="text" name="name" placeholder="{{ __('app.doctor_name_placeholder') }}" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.department') }}</label>
                    <select name="department_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.specialization') }}</label>
                    <input type="text" name="specialization" placeholder="{{ __('app.specialization_placeholder') }}" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.email') }}</label>
                    <input type="email" name="email" placeholder="{{ __('app.doctor_email_placeholder') }}" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" placeholder="+1 555-0199" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.consultation_fee') }} ($)</label>
                    <input type="number" step="0.01" name="consultation_fee" value="100.00" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.status') }}</label>
                    <select name="status" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="active">{{ __('app.status_available') }}</option>
                        <option value="on_leave">{{ __('app.status_maintenance') }}</option>
                        <option value="inactive">{{ __('app.status_cancelled') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Available Days</label>
                    <input type="text" name="available_days" value="Mon, Tue, Wed, Thu, Fri" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Available Hours</label>
                    <input type="text" name="available_time" value="09:00 AM - 05:00 PM" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addDoctorModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">{{ __('app.cancel') }}</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">{{ __('app.register_doctor') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

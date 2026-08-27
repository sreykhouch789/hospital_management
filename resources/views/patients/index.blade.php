@extends('layouts.app')

@section('title', __('app.patients_title'))

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-procedures text-emerald-400"></i> {{ __('app.patients_title') }}
        </h1>
        <p class="text-xs text-slate-400">{{ __('app.patients_subtitle') }}</p>
    </div>
    
    <button onclick="document.getElementById('addPatientModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> {{ __('app.add_patient') }}
    </button>
</div>

<!-- Search Bar -->
<form method="GET" action="{{ route('patients.index') }}" class="glass-panel p-4 rounded-xl border border-slate-800 flex gap-4">
    <div class="flex-1 relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_placeholder') }}" class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-200 rounded-lg pl-8 pr-4 py-2 focus:outline-none focus:border-emerald-500">
    </div>
    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs px-4 py-2 rounded-lg border border-slate-700">{{ __('app.search') }}</button>
</form>

<!-- Patients Table -->
<div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-400 uppercase bg-slate-900/90 border-b border-slate-800">
                <tr>
                    <th class="py-3.5 px-4">{{ __('app.mrn') }}</th>
                    <th class="py-3.5 px-4">{{ __('app.full_name') }}</th>
                    <th class="py-3.5 px-4">{{ __('app.age') }} / {{ __('app.gender') }}</th>
                    <th class="py-3.5 px-4">Blood Group</th>
                    <th class="py-3.5 px-4">{{ __('app.phone') }}</th>
                    <th class="py-3.5 px-4">{{ __('app.medical_history') }}</th>
                    <th class="py-3.5 px-4 text-right">{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($patients as $patient)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-emerald-400">{{ $patient->mrn }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-white">{{ $patient->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $patient->email ?? 'No email' }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-300">{{ $patient->age }} / {{ $patient->gender }}</td>
                        <td class="py-3.5 px-4">
                            <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded font-mono font-bold text-[11px]">
                                {{ $patient->blood_group }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-300">{{ $patient->phone }}</td>
                        <td class="py-3.5 px-4 text-slate-400 max-w-xs truncate">{{ $patient->medical_history ?? __('app.na') }}</td>
                        <td class="py-3.5 px-4 text-right">
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')" class="inline">
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
                        <td colspan="7" class="text-center py-8 text-slate-500">{{ __('app.no_patients') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $patients->links() }}
</div>

<!-- Modal: Register Patient -->
<div id="addPatientModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-emerald-400"></i> {{ __('app.add_patient') }}
            </h3>
            <button onclick="document.getElementById('addPatientModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('patients.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-medium mb-1">{{ __('app.full_name') }}</label>
                <input type="text" name="name" placeholder="{{ __('app.full_name_placeholder') }}" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.age') }}</label>
                    <input type="number" name="age" placeholder="35" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.gender') }}</label>
                    <select name="gender" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="Male">{{ __('app.male') }}</option>
                        <option value="Female">{{ __('app.female') }}</option>
                        <option value="Other">{{ __('app.other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Blood Group</label>
                    <select name="blood_group" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" placeholder="+1 555-0100" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">{{ __('app.email') }}</label>
                    <input type="email" name="email" placeholder="patient@example.com" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">{{ __('app.address') }}</label>
                <input type="text" name="address" placeholder="{{ __('app.address_placeholder') }}" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">{{ __('app.medical_history') }}</label>
                <textarea name="medical_history" rows="2" placeholder="{{ __('app.history_placeholder') }}" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addPatientModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">{{ __('app.cancel') }}</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">{{ __('app.register_patient_submit') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

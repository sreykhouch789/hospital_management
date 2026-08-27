@extends('layouts.app')

@section('title', 'Patient Registry')

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-procedures text-emerald-400"></i> Patient Registry
        </h1>
        <p class="text-xs text-slate-400">Search patient records, medical history, and contact details.</p>
    </div>
    
    <button onclick="document.getElementById('addPatientModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> Register Patient
    </button>
</div>

<!-- Search Bar -->
<form method="GET" action="{{ route('patients.index') }}" class="glass-panel p-4 rounded-xl border border-slate-800 flex gap-4">
    <div class="flex-1 relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by MRN, Patient Name, or Phone..." class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-200 rounded-lg pl-8 pr-4 py-2 focus:outline-none focus:border-emerald-500">
    </div>
    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs px-4 py-2 rounded-lg border border-slate-700">Filter</button>
</form>

<!-- Patients Table -->
<div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-400 uppercase bg-slate-900/90 border-b border-slate-800">
                <tr>
                    <th class="py-3.5 px-4">MRN</th>
                    <th class="py-3.5 px-4">Patient Name</th>
                    <th class="py-3.5 px-4">Age / Gender</th>
                    <th class="py-3.5 px-4">Blood Group</th>
                    <th class="py-3.5 px-4">Phone</th>
                    <th class="py-3.5 px-4">Medical History</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
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
                        <td class="py-3.5 px-4 text-slate-300">{{ $patient->age }} yrs / {{ $patient->gender }}</td>
                        <td class="py-3.5 px-4">
                            <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded font-mono font-bold text-[11px]">
                                {{ $patient->blood_group }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-300">{{ $patient->phone }}</td>
                        <td class="py-3.5 px-4 text-slate-400 max-w-xs truncate">{{ $patient->medical_history ?? 'None recorded' }}</td>
                        <td class="py-3.5 px-4 text-right">
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Delete patient record?')" class="inline">
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
                        <td colspan="7" class="text-center py-8 text-slate-500">No patient records found.</td>
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
                <i class="fa-solid fa-user-plus text-emerald-400"></i> New Patient Entry
            </h3>
            <button onclick="document.getElementById('addPatientModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('patients.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-medium mb-1">Patient Full Name</label>
                <input type="text" name="name" placeholder="Johnathan Miller" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Age</label>
                    <input type="number" name="age" placeholder="35" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Gender</label>
                    <select name="gender" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
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
                    <label class="block text-slate-300 font-medium mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="+1 555-0100" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="patient@example.com" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Address</label>
                <input type="text" name="address" placeholder="Residential Street Address" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Medical History & Allergies</label>
                <textarea name="medical_history" rows="2" placeholder="Existing conditions, allergies, or past surgeries..." class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addPatientModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">Register Patient</button>
            </div>
        </form>
    </div>
</div>
@endsection

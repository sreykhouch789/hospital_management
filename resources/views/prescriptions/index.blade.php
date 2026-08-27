@extends('layouts.app')

@section('title', 'Medical Prescriptions')

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-prescription-bottle-medical text-emerald-400"></i> Prescriptions & Lab Requests
        </h1>
        <p class="text-xs text-slate-400">Create digital prescriptions, dosages, and diagnostic lab test orders.</p>
    </div>
    
    <button onclick="document.getElementById('addPrescriptionModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-file-signature"></i> Create Prescription
    </button>
</div>

<!-- Prescriptions Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($prescriptions as $script)
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 flex flex-col justify-between hover:border-emerald-500/40 transition-all">
            <div>
                <div class="flex items-start justify-between border-b border-slate-800 pb-3">
                    <div>
                        <span class="text-[10px] font-mono bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded">
                            Rx Date: {{ $script->prescription_date->format('M d, Y') }}
                        </span>
                        <h3 class="text-base font-bold text-white mt-2">{{ $script->patient->name ?? 'N/A' }}</h3>
                        <p class="text-xs text-slate-400 font-mono">MRN: {{ $script->patient->mrn ?? 'N/A' }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-xs font-semibold text-slate-200 block">{{ $script->doctor->name ?? 'N/A' }}</span>
                        <span class="text-[11px] text-emerald-400 block">{{ $script->doctor->specialization ?? '' }}</span>
                    </div>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div>
                        <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Diagnosis:</span>
                        <p class="text-slate-200 font-semibold mt-0.5 bg-slate-900/80 p-2 rounded border border-slate-800">{{ $script->diagnosis }}</p>
                    </div>

                    <div>
                        <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Medicines Prescribed:</span>
                        <pre class="text-emerald-300 font-sans mt-0.5 bg-slate-950 p-2.5 rounded border border-slate-800 whitespace-pre-wrap leading-relaxed">{{ $script->medicines }}</pre>
                    </div>

                    @if($script->dosage_instructions)
                        <div>
                            <span class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Dosage & Instructions:</span>
                            <p class="text-slate-300 mt-0.5 italic">{{ $script->dosage_instructions }}</p>
                        </div>
                    @endif

                    @if($script->lab_tests)
                        <div>
                            <span class="font-bold text-cyan-400 uppercase tracking-wider text-[10px]">Lab Tests Requested:</span>
                            <p class="text-cyan-200 mt-0.5 font-medium">{{ $script->lab_tests }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-5 pt-3 border-t border-slate-800 flex items-center justify-between">
                <span class="text-[10px] text-slate-500">MediPulse Rx System</span>
                <form action="{{ route('prescriptions.destroy', $script->id) }}" method="POST" onsubmit="return confirm('Delete prescription?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-slate-500 hover:text-rose-400 text-xs">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-slate-500 glass-panel rounded-2xl">
            <i class="fa-solid fa-prescription-bottle text-3xl mb-2"></i>
            <p>No prescriptions created yet.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $prescriptions->links() }}
</div>

<!-- Modal: Create Prescription -->
<div id="addPrescriptionModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-file-signature text-emerald-400"></i> New Prescription Form
            </h3>
            <button onclick="document.getElementById('addPrescriptionModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('prescriptions.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Patient</label>
                    <select name="patient_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Attending Doctor</label>
                    <select name="doctor_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Associated Appointment</label>
                    <select name="appointment_id" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="">-- None / Walk-in --</option>
                        @foreach($appointments as $apt)
                            <option value="{{ $apt->id }}">{{ $apt->appointment_number }} - {{ $apt->patient->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Prescription Date</label>
                    <input type="date" name="prescription_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Diagnosis</label>
                <input type="text" name="diagnosis" placeholder="Acute Bronchitis, Migraine, Hypertension..." required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Medicines List & Dosage</label>
                <textarea name="medicines" rows="3" placeholder="1. Amoxicillin 500mg - 1 tab thrice daily&#10;2. Paracetamol 500mg - 1 tab as needed for fever" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500 font-mono"></textarea>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Dosage & Usage Instructions</label>
                <input type="text" name="dosage_instructions" placeholder="Take after meals. Drink plenty of warm water." class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Lab Tests (If Required)</label>
                <input type="text" name="lab_tests" placeholder="Complete Blood Count, Chest X-Ray..." class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addPrescriptionModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">Save Rx</button>
            </div>
        </form>
    </div>
</div>
@endsection

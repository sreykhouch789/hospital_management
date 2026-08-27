@extends('layouts.app')

@section('title', __('app.prescriptions_title'))

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-prescription-bottle-medical text-emerald-600"></i> {{ __('app.prescriptions_title') }}
        </h1>
        <p class="text-xs text-slate-500">{{ __('app.prescriptions_subtitle') }}</p>
    </div>
    
    <button onclick="document.getElementById('addPrescriptionModal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-file-signature"></i> {{ __('app.add_prescription') }}
    </button>
</div>

<!-- Prescriptions Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($prescriptions as $script)
        <div class="glass-panel p-6 rounded-2xl border border-slate-200 flex flex-col justify-between hover:border-emerald-400 transition-all">
            <div>
                <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-mono bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded font-medium">
                            Rx Date: {{ $script->prescription_date->format('M d, Y') }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 mt-2">{{ $script->patient->name ?? __('app.na') }}</h3>
                        <p class="text-xs text-slate-500 font-mono">MRN: {{ $script->patient->mrn ?? __('app.na') }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-xs font-semibold text-slate-800 block">{{ $script->doctor->name ?? __('app.na') }}</span>
                        <span class="text-[11px] text-emerald-600 font-medium block">{{ $script->doctor->specialization ?? '' }}</span>
                    </div>
                </div>

                <div class="mt-4 space-y-3 text-xs">
                    <div>
                        <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">{{ __('app.diagnosis') }}:</span>
                        <p class="text-slate-800 font-semibold mt-0.5 bg-slate-50 p-2 rounded border border-slate-200">{{ $script->diagnosis }}</p>
                    </div>

                    <div>
                        <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">{{ __('app.medication') }}:</span>
                        <pre class="text-emerald-800 font-sans mt-0.5 bg-emerald-50/50 p-2.5 rounded border border-emerald-100 whitespace-pre-wrap leading-relaxed font-semibold">{{ $script->medicines }}</pre>
                    </div>

                    @if($script->dosage_instructions)
                        <div>
                            <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">{{ __('app.dosage_instructions') }}:</span>
                            <p class="text-slate-600 mt-0.5 italic">{{ $script->dosage_instructions }}</p>
                        </div>
                    @endif

                    @if($script->lab_tests)
                        <div>
                            <span class="font-bold text-cyan-700 uppercase tracking-wider text-[10px]">Lab Tests Requested:</span>
                            <p class="text-cyan-800 mt-0.5 font-medium bg-cyan-50 p-2 rounded border border-cyan-100">{{ $script->lab_tests }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10px] text-slate-400">MediPulse Rx System</span>
                <form action="{{ route('prescriptions.destroy', $script->id) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-slate-400 hover:text-rose-600 text-xs transition-colors">
                        <i class="fa-solid fa-trash-can"></i> {{ __('app.delete') }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-slate-400 glass-panel rounded-2xl">
            <i class="fa-solid fa-prescription-bottle text-3xl mb-2"></i>
            <p>{{ __('app.no_prescriptions') }}</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $prescriptions->links() }}
</div>

<!-- Modal: Create Prescription -->
<div id="addPrescriptionModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-file-signature text-emerald-600"></i> {{ __('app.add_prescription') }}
            </h3>
            <button onclick="document.getElementById('addPrescriptionModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('prescriptions.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.patient') }}</label>
                    <select name="patient_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.doctor') }}</label>
                    <select name="doctor_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.appointment') }}</label>
                    <select name="appointment_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="">-- None / Walk-in --</option>
                        @foreach($appointments as $apt)
                            <option value="{{ $apt->id }}">{{ $apt->appointment_number }} - {{ $apt->patient->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-medium mb-1">Prescription Date</label>
                    <input type="date" name="prescription_date" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.diagnosis') }}</label>
                <input type="text" name="diagnosis" placeholder="{{ __('app.diagnosis_placeholder') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.medication') }}</label>
                <textarea name="medicines" rows="3" placeholder="{{ __('app.medication_placeholder') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500 font-mono"></textarea>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.dosage_instructions') }}</label>
                <input type="text" name="dosage_instructions" placeholder="{{ __('app.dosage_placeholder') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">Lab Tests (If Required)</label>
                <input type="text" name="lab_tests" placeholder="Complete Blood Count, Chest X-Ray..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addPrescriptionModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">{{ __('app.cancel') }}</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md shadow-emerald-600/20">{{ __('app.issue_prescription') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', __('app.bills_title'))

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i> {{ __('app.bills_title') }}
        </h1>
        <p class="text-xs text-slate-500">{{ __('app.bills_subtitle') }}</p>
    </div>
    
    <button onclick="document.getElementById('addBillModal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-receipt"></i> {{ __('app.add_bill') }}
    </button>
</div>

<!-- Bills Table -->
<div class="glass-panel rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-4">{{ __('app.invoice_no') }}</th>
                    <th class="py-3.5 px-4">{{ __('app.patient') }}</th>
                    <th class="py-3.5 px-4">Fee Breakdown</th>
                    <th class="py-3.5 px-4">Tax (5%)</th>
                    <th class="py-3.5 px-4">{{ __('app.total_amount') }}</th>
                    <th class="py-3.5 px-4">Payment Method</th>
                    <th class="py-3.5 px-4">{{ __('app.status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bills as $bill)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-emerald-600">{{ $bill->invoice_number }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900">{{ $bill->patient->name ?? __('app.na') }}</div>
                            <div class="text-[11px] text-slate-500 font-mono">{{ $bill->patient->mrn ?? '' }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            <div>Consultation: ${{ number_format($bill->consultation_fee, 2) }}</div>
                            <div>Room / Bed: ${{ number_format($bill->room_charge, 2) }}</div>
                            <div>Medicine: ${{ number_format($bill->medicine_charge, 2) }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500">${{ number_format($bill->tax, 2) }}</td>
                        <td class="py-3.5 px-4 font-mono font-bold text-base text-slate-900">${{ number_format($bill->total_amount, 2) }}</td>
                        <td class="py-3.5 px-4 text-slate-700 font-medium">{{ $bill->payment_method }}</td>
                        <td class="py-3.5 px-4">
                            <form action="{{ route('bills.updateStatus', $bill->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-white border text-[11px] font-semibold rounded px-2 py-1 focus:outline-none 
                                    @if($bill->status === 'Paid') border-emerald-300 text-emerald-700 bg-emerald-50
                                    @elseif($bill->status === 'Partially Paid') border-amber-300 text-amber-700 bg-amber-50
                                    @else border-rose-300 text-rose-700 bg-rose-50 @endif">
                                    <option value="Unpaid" {{ $bill->status === 'Unpaid' ? 'selected' : '' }}>{{ __('app.status_unpaid') }}</option>
                                    <option value="Paid" {{ $bill->status === 'Paid' ? 'selected' : '' }}>{{ __('app.status_paid') }}</option>
                                    <option value="Partially Paid" {{ $bill->status === 'Partially Paid' ? 'selected' : '' }}>{{ __('app.status_partial') }}</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400">{{ __('app.no_bills') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $bills->links() }}
</div>

<!-- Modal: Create Bill -->
<div id="addBillModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-emerald-600"></i> {{ __('app.add_bill') }}
            </h3>
            <button onclick="document.getElementById('addBillModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('bills.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.select_patient') }}</label>
                <select name="patient_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.select_appointment') }}</label>
                <select name="appointment_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    <option value="">{{ __('app.choose_appointment') }}</option>
                    @foreach($appointments as $apt)
                        <option value="{{ $apt->id }}">{{ $apt->appointment_number }} - {{ $apt->patient->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.consultation_fee') }} ($)</label>
                    <input type="number" step="0.01" name="consultation_fee" value="100.00" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.room_charges') }} ($)</label>
                    <input type="number" step="0.01" name="room_charge" value="0.00" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.medication_fee') }} ($)</label>
                    <input type="number" step="0.01" name="medicine_charge" value="25.00" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-700 font-medium mb-1">Payment Method</label>
                    <select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="Credit Card">Credit Card</option>
                        <option value="Cash">Cash</option>
                        <option value="Insurance">Insurance</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-medium mb-1">{{ __('app.status') }}</label>
                    <select name="status" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="Unpaid">{{ __('app.status_unpaid') }}</option>
                        <option value="Paid">{{ __('app.status_paid') }}</option>
                        <option value="Partially Paid">{{ __('app.status_partial') }}</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addBillModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">{{ __('app.cancel') }}</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md shadow-emerald-600/20">{{ __('app.create_invoice') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


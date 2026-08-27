@extends('layouts.app')

@section('title', 'Billing & Invoices')

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-emerald-400"></i> Billing & Invoice Management
        </h1>
        <p class="text-xs text-slate-400">Generate patient invoices, record payments, and track outstanding balances.</p>
    </div>
    
    <button onclick="document.getElementById('addBillModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-receipt"></i> Create New Invoice
    </button>
</div>

<!-- Bills Table -->
<div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-400 uppercase bg-slate-900/90 border-b border-slate-800">
                <tr>
                    <th class="py-3.5 px-4">Invoice #</th>
                    <th class="py-3.5 px-4">Patient</th>
                    <th class="py-3.5 px-4">Fee Breakdown</th>
                    <th class="py-3.5 px-4">Tax (5%)</th>
                    <th class="py-3.5 px-4">Total Amount</th>
                    <th class="py-3.5 px-4">Payment Method</th>
                    <th class="py-3.5 px-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
                @forelse($bills as $bill)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-emerald-400">{{ $bill->invoice_number }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-white">{{ $bill->patient->name ?? 'N/A' }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $bill->patient->mrn ?? '' }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-300">
                            <div>Consultation: ${{ number_format($bill->consultation_fee, 2) }}</div>
                            <div>Room / Bed: ${{ number_format($bill->room_charge, 2) }}</div>
                            <div>Medicine: ${{ number_format($bill->medicine_charge, 2) }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-400">${{ number_format($bill->tax, 2) }}</td>
                        <td class="py-3.5 px-4 font-mono font-bold text-base text-white">${{ number_format($bill->total_amount, 2) }}</td>
                        <td class="py-3.5 px-4 text-slate-300 font-medium">{{ $bill->payment_method }}</td>
                        <td class="py-3.5 px-4">
                            <form action="{{ route('bills.updateStatus', $bill->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-slate-900 border text-[11px] font-semibold rounded px-2 py-1 focus:outline-none 
                                    @if($bill->status === 'Paid') border-emerald-500/40 text-emerald-400
                                    @elseif($bill->status === 'Partially Paid') border-amber-500/40 text-amber-400
                                    @else border-rose-500/40 text-rose-400 @endif">
                                    <option value="Unpaid" {{ $bill->status === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="Paid" {{ $bill->status === 'Paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="Partially Paid" {{ $bill->status === 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-500">No invoices generated yet.</td>
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
<div id="addBillModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-receipt text-emerald-400"></i> Generate Patient Invoice
            </h3>
            <button onclick="document.getElementById('addBillModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('bills.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-medium mb-1">Select Patient</label>
                <select name="patient_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Associated Appointment (Optional)</label>
                <select name="appointment_id" class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    <option value="">-- None --</option>
                    @foreach($appointments as $apt)
                        <option value="{{ $apt->id }}">{{ $apt->appointment_number }} - {{ $apt->patient->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Consultation ($)</label>
                    <input type="number" step="0.01" name="consultation_fee" value="100.00" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Room Charge ($)</label>
                    <input type="number" step="0.01" name="room_charge" value="0.00" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Medicine ($)</label>
                    <input type="number" step="0.01" name="medicine_charge" value="25.00" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Payment Method</label>
                    <select name="payment_method" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="Credit Card">Credit Card</option>
                        <option value="Cash">Cash</option>
                        <option value="Insurance">Insurance</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-medium mb-1">Payment Status</label>
                    <select name="status" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                        <option value="Unpaid">Unpaid</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                    </select>
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addBillModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">Create Invoice</button>
            </div>
        </form>
    </div>
</div>
@endsection

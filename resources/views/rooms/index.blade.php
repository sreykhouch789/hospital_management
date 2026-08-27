@extends('layouts.app')

@section('title', 'Rooms & Inpatient Beds')

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-bed text-emerald-400"></i> Rooms & Inpatient Bed Allocation
        </h1>
        <p class="text-xs text-slate-400">Monitor ICU, Private Rooms, and Ward capacity in real-time.</p>
    </div>
    
    <button onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add New Room
    </button>
</div>

<!-- Rooms Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($rooms as $room)
        <div class="glass-panel p-5 rounded-2xl border border-slate-800 flex flex-col justify-between hover:border-emerald-500/40 transition-all relative">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-black font-mono text-white">{{ $room->room_number }}</span>
                    @if($room->status === 'Available')
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded text-[10px] font-bold">
                            Available
                        </span>
                    @elseif($room->status === 'Occupied')
                        <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 px-2 py-0.5 rounded text-[10px] font-bold">
                            Occupied
                        </span>
                    @else
                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded text-[10px] font-bold">
                            Maintenance
                        </span>
                    @endif
                </div>

                <div class="mt-3 space-y-1.5 text-xs">
                    <div class="text-slate-400">Type: <span class="text-slate-200 font-semibold">{{ $room->type }}</span></div>
                    <div class="text-slate-400">Rate: <span class="text-emerald-400 font-mono font-bold">${{ number_format($room->daily_rate, 2) }}</span> / day</div>
                    
                    @if($room->patient)
                        <div class="mt-3 p-2.5 rounded-xl bg-slate-900 border border-slate-800">
                            <span class="text-[10px] text-slate-500 block uppercase font-bold">Admitted Patient:</span>
                            <span class="text-xs font-bold text-white block mt-0.5">{{ $room->patient->name }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">MRN: {{ $room->patient->mrn }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800 flex items-center justify-between">
                @if($room->status === 'Available')
                    <button onclick="openAssignModal({{ $room->id }}, '{{ $room->room_number }}')" class="w-full bg-slate-800 hover:bg-slate-700 text-emerald-400 text-xs font-semibold py-1.5 rounded-lg border border-slate-700">
                        Assign Patient
                    </button>
                @elseif($room->status === 'Occupied')
                    <form action="{{ route('rooms.discharge', $room->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('Discharge patient from this room?')" class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs font-semibold py-1.5 rounded-lg border border-rose-500/30">
                            Discharge Patient
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-slate-500 glass-panel rounded-2xl">
            <i class="fa-solid fa-bed text-3xl mb-2"></i>
            <p>No rooms added yet.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $rooms->links() }}
</div>

<!-- Modal: Add Room -->
<div id="addRoomModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-plus text-emerald-400"></i> Add Hospital Room
            </h3>
            <button onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('rooms.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-300 font-medium mb-1">Room Code / Number</label>
                <input type="text" name="room_number" placeholder="ICU-102 or PRIV-201" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Room Category</label>
                <select name="type" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    <option value="General Ward">General Ward</option>
                    <option value="Private Room">Private Room</option>
                    <option value="Semi-Private">Semi-Private</option>
                    <option value="ICU">ICU</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>

            <div>
                <label class="block text-slate-300 font-medium mb-1">Daily Rate ($)</label>
                <input type="number" step="0.01" name="daily_rate" value="150.00" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">Add Room</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Assign Patient to Room -->
<div id="assignModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-bed text-emerald-400"></i> Admit Patient to <span id="roomNumberText" class="font-mono text-emerald-400"></span>
            </h3>
            <button onclick="document.getElementById('assignModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="assignForm" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-slate-300 font-medium mb-1">Select Patient to Admit</label>
                <select name="patient_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('assignModal').classList.add('hidden')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg shadow-md shadow-emerald-500/20">Admit Patient</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAssignModal(roomId, roomNumber) {
        document.getElementById('roomNumberText').innerText = roomNumber;
        document.getElementById('assignForm').action = "/rooms/" + roomId + "/assign";
        document.getElementById('assignModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection

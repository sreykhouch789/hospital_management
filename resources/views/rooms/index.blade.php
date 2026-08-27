@extends('layouts.app')

@section('title', __('app.rooms_title'))

@section('content')
<!-- Header & Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-bed text-emerald-600"></i> {{ __('app.rooms_title') }}
        </h1>
        <p class="text-xs text-slate-500">{{ __('app.rooms_subtitle') }}</p>
    </div>
    
    <button onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> {{ __('app.add_room') }}
    </button>
</div>

<!-- Rooms Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($rooms as $room)
        <div class="glass-panel p-5 rounded-2xl border border-slate-200 flex flex-col justify-between hover:border-emerald-400 transition-all relative">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-black font-mono text-slate-900">{{ $room->room_number }}</span>
                    @if($room->status === 'Available')
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded text-[10px] font-bold">
                            {{ __('app.status_available') }}
                        </span>
                    @elseif($room->status === 'Occupied')
                        <span class="bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 rounded text-[10px] font-bold">
                            {{ __('app.status_occupied') }}
                        </span>
                    @else
                        <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded text-[10px] font-bold">
                            {{ __('app.status_maintenance') }}
                        </span>
                    @endif
                </div>

                <div class="mt-3 space-y-1.5 text-xs">
                    <div class="text-slate-500">{{ __('app.room_type') }}: <span class="text-slate-800 font-semibold">{{ $room->type }}</span></div>
                    <div class="text-slate-500">{{ __('app.daily_rate') }}: <span class="text-emerald-700 font-mono font-bold">${{ number_format($room->daily_rate, 2) }}</span> / day</div>
                    
                    @if($room->patient)
                        <div class="mt-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200">
                            <span class="text-[10px] text-slate-500 block uppercase font-bold">{{ __('app.current_patient') }}:</span>
                            <span class="text-xs font-bold text-slate-900 block mt-0.5">{{ $room->patient->name }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">MRN: {{ $room->patient->mrn }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                @if($room->status === 'Available')
                    <button onclick="openAssignModal({{ $room->id }}, '{{ $room->room_number }}')" class="w-full bg-slate-100 hover:bg-slate-200 text-emerald-700 text-xs font-bold py-1.5 rounded-lg border border-slate-200 transition-colors">
                        {{ __('app.assign_patient') }}
                    </button>
                @elseif($room->status === 'Occupied')
                    <form action="{{ route('rooms.discharge', $room->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('{{ __('app.discharge_confirm') }}')" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold py-1.5 rounded-lg border border-rose-200 transition-colors">
                            {{ __('app.discharge_patient') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-slate-400 glass-panel rounded-2xl">
            <i class="fa-solid fa-bed text-3xl mb-2"></i>
            <p>{{ __('app.no_rooms') }}</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $rooms->links() }}
</div>

<!-- Modal: Add Room -->
<div id="addRoomModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-plus text-emerald-600"></i> {{ __('app.add_room') }}
            </h3>
            <button onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('rooms.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.room_no') }}</label>
                <input type="text" name="room_number" placeholder="{{ __('app.room_number_placeholder') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.room_type') }}</label>
                <select name="type" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    <option value="General Ward">General Ward</option>
                    <option value="Private Room">Private Room</option>
                    <option value="Semi-Private">Semi-Private</option>
                    <option value="ICU">ICU</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>

            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.daily_rate') }} ($)</label>
                <input type="number" step="0.01" name="daily_rate" value="150.00" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">{{ __('app.cancel') }}</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md shadow-emerald-600/20">{{ __('app.create_room') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Assign Patient to Room -->
<div id="assignModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bed text-emerald-600"></i> {{ __('app.assign_patient_modal') }} <span id="roomNumberText" class="font-mono text-emerald-600"></span>
            </h3>
            <button onclick="document.getElementById('assignModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="assignForm" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-slate-700 font-medium mb-1">{{ __('app.select_patient') }}</label>
                <select name="patient_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg px-3 py-2 focus:outline-none focus:border-emerald-500">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->mrn }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('assignModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg">{{ __('app.cancel') }}</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md shadow-emerald-600/20">{{ __('app.assign') }}</button>
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


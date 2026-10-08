<x-app-layout>
    <x-slot name="header">Form Pengajuan Peminjaman Ruangan</x-slot>

    @php
        $input = 'w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
        $readonly = 'w-full rounded-lg border-slate-200 bg-slate-100 text-sm text-slate-600';
        $user = auth()->user();
        $idLabel = $user->category === 'dosen' ? 'NIDN / NIP' : 'NIM';
    @endphp

    <form method="POST" action="{{ route('bookings.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-8 max-w-3xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Peminjam</label>
                <input type="text" value="{{ $user->name }}" class="{{ $readonly }}" readonly>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">{{ $idLabel }}</label>
                <input type="text" value="{{ $user->identifier }}" class="{{ $readonly }}" readonly>
            </div>

            <div class="md:col-span-2">
                <label for="room_id" class="block text-sm font-semibold text-slate-700 mb-1">Pilih Ruangan</label>
                <select id="room_id" name="room_id" required class="{{ $input }}">
                    <option value="">- Pilih ruangan -</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected((string) old('room_id', request('room_id')) === (string) $room->id)>
                            {{ $room->name }} ({{ $room->type === 'laboratorium' ? 'Lab' : 'Kelas' }}, kapasitas {{ $room->capacity }})
                        </option>
                    @endforeach
                </select>
                @error('room_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="date" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
                <input id="date" type="date" name="date" required min="{{ today()->toDateString() }}"
                       value="{{ old('date', request('date', today()->toDateString())) }}" class="{{ $input }}">
                @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="participants" class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Peserta</label>
                <input id="participants" type="number" name="participants" required min="1"
                       value="{{ old('participants') }}" class="{{ $input }}">
                @error('participants') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="start_time" class="block text-sm font-semibold text-slate-700 mb-1">Jam Mulai</label>
                <input id="start_time" type="time" name="start_time" required step="1800"
                       min="{{ config('siperu.open_time') }}" max="{{ config('siperu.close_time') }}"
                       value="{{ old('start_time', request('start_time')) }}" class="{{ $input }}">
                @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="end_time" class="block text-sm font-semibold text-slate-700 mb-1">Jam Selesai</label>
                <input id="end_time" type="time" name="end_time" required step="1800"
                       min="{{ config('siperu.open_time') }}" max="{{ config('siperu.close_time') }}"
                       value="{{ old('end_time', request('end_time')) }}" class="{{ $input }}">
                @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="purpose" class="block text-sm font-semibold text-slate-700 mb-1">Keperluan</label>
                <textarea id="purpose" name="purpose" rows="3" required maxlength="500"
                          placeholder="Contoh: Praktikum Pemrograman Web" class="{{ $input }}">{{ old('purpose') }}</textarea>
                @error('purpose') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="letter" class="block text-sm font-semibold text-slate-700 mb-1">Upload Surat Izin (PDF / JPG, maks. 2 MB)</label>
                <input id="letter" type="file" name="letter" required accept=".pdf,.jpg,.jpeg,application/pdf,image/jpeg"
                       class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                @error('letter') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <p class="mt-5 text-xs text-slate-500">
            Jam operasional peminjaman {{ config('siperu.open_time') }} - {{ config('siperu.close_time') }}.
            Jika jam yang sama sudah diajukan orang lain, pengajuan Anda otomatis masuk antrean.
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('dashboard') }}" class="rounded-lg bg-slate-100 hover:bg-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition">Batal</a>
            <button type="submit" class="rounded-lg bg-blue-900 hover:bg-blue-800 px-5 py-2.5 text-sm font-semibold text-white transition">Kirim Pengajuan</button>
        </div>
    </form>
</x-app-layout>

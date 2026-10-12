@php($editing = $editing ?? false)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Nama Ruangan
        </label>

        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" placeholder="Contoh: HP 1"
            required class="w-full rounded-lg border-slate-200">

        @error('name')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Gedung / Lokasi
        </label>

        <input type="text" name="building" value="{{ old('building', $room->building ?? '') }}"
            placeholder="Contoh: Gedung Teknik" class="w-full rounded-lg border-slate-200">

        @error('building')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Jenis Ruangan
        </label>

        <select name="type" required class="w-full rounded-lg border-slate-200">
            <option value="kelas" @selected(old('type', $room->type ?? '') === 'kelas')>
                Kelas
            </option>

            <option value="laboratorium" @selected(old('type', $room->type ?? '') === 'laboratorium')>
                Laboratorium
            </option>
        </select>

        @error('type')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Kapasitas
        </label>

        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity ?? '') }}" min="1"
            required class="w-full rounded-lg border-slate-200">

        @error('capacity')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Kelompok PJ
        </label>

        <select name="pj_group" required class="w-full rounded-lg border-slate-200">
            <option value="">- Pilih Kelompok PJ -</option>

            @foreach ($pjGroups as $key => $label)
                <option value="{{ $key }}" @selected(old('pj_group', $room->pj_group ?? '') === $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <p class="text-xs text-slate-500 mt-1">
            PJ bertanggung jawab berdasarkan kelompok ruangan, bukan per ruangan individual.
        </p>

        @error('pj_group')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Fasilitas
        </label>

        <textarea name="facilities" rows="4" placeholder="Contoh: Proyektor, AC, Papan Tulis, Komputer"
            class="w-full rounded-lg border-slate-200">{{ old('facilities', isset($room) ? implode(', ', $room->facilities ?? []) : '') }}</textarea>

        <p class="text-xs text-slate-500 mt-1">
            Pisahkan fasilitas menggunakan koma. Contoh: Proyektor, AC, Papan Tulis.
        </p>

        @error('facilities')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $room->is_active ?? true))
                class="rounded border-slate-300">

            <span class="text-sm font-medium text-slate-700">
                Ruangan aktif dan dapat digunakan untuk peminjaman
            </span>
        </label>
    </div>
</div>

@php($editing = $editing ?? false)

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="w-full rounded-lg border-slate-200">
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Identifier / NIM / NIP</label>
        <input type="text" name="identifier" value="{{ old('identifier', $user->identifier ?? '') }}" required class="w-full rounded-lg border-slate-200">
        @error('identifier')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="w-full rounded-lg border-slate-200">
        @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full rounded-lg border-slate-200">
        @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
        <select name="role" id="role" required class="w-full rounded-lg border-slate-200">
            <option value="peminjam" @selected(old('role', $user->role ?? '') === 'peminjam')>Peminjam</option>
            <option value="pj_ruangan" @selected(old('role', $user->role ?? '') === 'pj_ruangan')>PJ Ruangan/Lab</option>
            <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin Utama</option>
        </select>
        @error('role')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div id="category-wrapper">
        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Peminjam</label>
        <select name="category" class="w-full rounded-lg border-slate-200">
            <option value="">- Pilih -</option>
            <option value="mahasiswa" @selected(old('category', $user->category ?? '') === 'mahasiswa')>Mahasiswa</option>
            <option value="dosen" @selected(old('category', $user->category ?? '') === 'dosen')>Dosen</option>
        </select>
        @error('category')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div id="pj-group-wrapper">
        <label class="block text-sm font-semibold text-slate-700 mb-2">Kelompok PJ</label>
        <select name="pj_group" class="w-full rounded-lg border-slate-200">
            <option value="">- Pilih Kelompok -</option>
            @foreach ($pjGroups as $key => $label)
                <option value="{{ $key }}" @selected(old('pj_group', $user->pj_group ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('pj_group')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Program Studi</label>
        <input type="text" name="program_studi" value="{{ old('program_studi', $user->program_studi ?? '') }}" class="w-full rounded-lg border-slate-200">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Password {{ $editing ? '(kosongkan jika tidak diubah)' : '' }}
        </label>
        <input type="password" name="password" class="w-full rounded-lg border-slate-200" {{ $editing ? '' : 'required' }}>
        @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded-lg border-slate-200" {{ $editing ? '' : 'required' }}>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) class="rounded border-slate-300">
            <span class="text-sm font-medium text-slate-700">Akun aktif</span>
        </label>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const role = document.getElementById('role');
    const categoryWrapper = document.getElementById('category-wrapper');
    const pjGroupWrapper = document.getElementById('pj-group-wrapper');

    function updateFields() {
        const isPj = role.value === 'pj_ruangan';
        const isPeminjam = role.value === 'peminjam';

        categoryWrapper.style.display = isPeminjam ? '' : 'none';
        pjGroupWrapper.style.display = isPj ? '' : 'none';
    }

    role.addEventListener('change', updateFields);
    updateFields();
});
</script>
<x-app-layout>
    <x-slot name="header">Edit Ruangan</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="space-y-5">
                @csrf
                @method('PUT')

                @include('admin.rooms.form', ['editing' => true])

                <div class="flex justify-end gap-3 pt-3">
                    <a
                        href="{{ route('admin.rooms.index') }}"
                        class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700"
                    >
                        Batal
                    </a>

                    <button class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
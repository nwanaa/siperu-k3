<x-app-layout>
    <x-slot name="header">Riwayat Peminjaman</x-slot>

    <p class="text-sm text-slate-500 mb-5">
        Pengajuan yang sudah selesai: disetujui dan sudah lewat, ditolak, atau dibatalkan.
    </p>

    @include('bookings.partials.list')
</x-app-layout>

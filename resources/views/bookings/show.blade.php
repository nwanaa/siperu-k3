<x-app-layout>
    <x-slot name="header">Detail Pengajuan {{ $booking->code }}</x-slot>

    @php
        $rows = [
            'Ruangan'       => $booking->room->name,
            'Tanggal'       => $booking->date->locale('id')->translatedFormat('l, d F Y'),
            'Jam'           => $booking->time_range,
            'Jumlah Peserta'=> $booking->participants . ' orang',
            'Keperluan'     => $booking->purpose,
            'PJ Ruangan'    => $booking->room->pjGroupLabel(),
            'Diajukan'      => $booking->created_at->locale('id')->translatedFormat('d M Y, H:i'),
        ];
        $canCancel = in_array($booking->status, \App\Enums\BookingStatus::open(), true);
    @endphp

    <div class="max-w-3xl space-y-5">

        @error('booking')
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $booking->status->badge() }}">{{ $booking->status->label() }}</span>
                @if ($booking->queue_position)
                    <span class="text-sm font-semibold text-orange-600">Posisi antrean: ke-{{ $booking->queue_position }}</span>
                @endif
            </div>

            <dl class="divide-y divide-slate-100 text-sm">
                @foreach ($rows as $label => $value)
                    <div class="grid grid-cols-3 gap-4 py-3">
                        <dt class="text-slate-500">{{ $label }}</dt>
                        <dd class="col-span-2 font-medium text-slate-800">{{ $value }}</dd>
                    </div>
                @endforeach
                <div class="grid grid-cols-3 gap-4 py-3">
                    <dt class="text-slate-500">Surat Izin</dt>
                    <dd class="col-span-2">
                        <a href="{{ route('bookings.letter', $booking) }}" target="_blank" rel="noopener"
                           class="font-semibold text-blue-600 hover:underline">Lihat dokumen</a>
                    </dd>
                </div>
            </dl>
        </div>

        @if ($booking->verified_at || $booking->verification_note)
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-8">
                <h3 class="font-bold text-slate-800 mb-3">Hasil Verifikasi</h3>
                <p class="text-sm text-slate-600">
                    {{ $booking->verifier ? 'Oleh ' . $booking->verifier->name : 'Diproses otomatis oleh sistem' }}
                    @if ($booking->verified_at)
                        pada {{ $booking->verified_at->locale('id')->translatedFormat('d M Y, H:i') }}
                    @endif
                </p>
                @if ($booking->verification_note)
                    <p class="mt-3 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">{{ $booking->verification_note }}</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap justify-between gap-3">
            <a href="{{ route('bookings.index') }}" class="rounded-lg bg-slate-100 hover:bg-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition">&larr; Kembali</a>

            @if ($canCancel)
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                      onsubmit="return confirm('Batalkan pengajuan {{ $booking->code }}? Tindakan ini tidak bisa diurungkan.')">
                    @csrf
                    <button type="submit" class="rounded-lg bg-red-50 hover:bg-red-100 px-5 py-2.5 text-sm font-semibold text-red-700 transition">Batalkan Pengajuan</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>

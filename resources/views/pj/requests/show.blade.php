<x-app-layout>
    <x-slot name="header">Detail Pengajuan {{ $booking->code }}</x-slot>

    <div class="max-w-6xl space-y-6">
        @error('booking')
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Nomor pengajuan</p>
                <h2 class="text-2xl font-bold text-blue-900">{{ $booking->code }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $booking->status->badge() }}">{{ $booking->status->label() }}</span>
                @if ($booking->queue_position)
                    <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-orange-50 text-orange-700">Antrean ke-{{ $booking->queue_position }}</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-7">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-bold text-slate-800">Informasi Pengajuan</h3>
                        <span class="text-xs text-slate-400">Diajukan {{ $booking->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</span>
                    </div>

                    <dl class="divide-y divide-slate-100 text-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Peminjam</dt>
                            <dd class="sm:col-span-2 font-semibold text-slate-800">{{ $booking->user->name }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Identitas</dt>
                            <dd class="sm:col-span-2 font-medium text-slate-800">{{ $booking->user->identifier }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Kategori</dt>
                            <dd class="sm:col-span-2 font-medium text-slate-800">{{ ucfirst($booking->user->category ?? 'Peminjam') }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="sm:col-span-2 font-medium text-slate-800 break-all">{{ $booking->user->email }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Ruangan</dt>
                            <dd class="sm:col-span-2 font-semibold text-slate-800">{{ $booking->room->name }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Tanggal</dt>
                            <dd class="sm:col-span-2 font-medium text-slate-800">{{ $booking->date->locale('id')->translatedFormat('l, d F Y') }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Jam</dt>
                            <dd class="sm:col-span-2 font-semibold text-slate-800">{{ $booking->time_range }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Jumlah Peserta</dt>
                            <dd class="sm:col-span-2 font-medium text-slate-800">{{ $booking->participants }} orang</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Keperluan</dt>
                            <dd class="sm:col-span-2 font-medium text-slate-800">{{ $booking->purpose }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 py-3">
                            <dt class="text-slate-500">Surat Izin</dt>
                            <dd class="sm:col-span-2">
                                @if ($booking->letter_path)
                                    <a href="{{ route('bookings.letter', $booking) }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 font-semibold text-blue-600 hover:underline">
                                        <x-icon name="document" class="w-4 h-4" />
                                        Lihat dokumen
                                    </a>
                                @else
                                    <span class="text-slate-400">Tidak ada dokumen</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-7">
                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div>
                            <h3 class="font-bold text-slate-800">Benturan Jadwal</h3>
                            <p class="text-xs text-slate-500 mt-1">Permintaan aktif yang waktunya bertabrakan dengan pengajuan ini.</p>
                        </div>
                        <span class="text-xs font-semibold text-slate-400">{{ $conflicts->count() }} data</span>
                    </div>

                    @forelse ($conflicts as $conflict)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 py-3 {{ ! $loop->last ? 'border-b border-slate-100' : '' }}">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $conflict->user->name }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $conflict->time_range }} · diajukan {{ $conflict->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $conflict->status->badge() }}">{{ $conflict->status->label() }}</span>
                                @if ($conflict->queue_position)
                                    <span class="text-xs font-semibold text-orange-600">Antrean #{{ $conflict->queue_position }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg bg-emerald-50 border border-emerald-100 p-4 text-sm text-emerald-700">
                            Tidak ada pengajuan aktif lain yang bertabrakan dengan waktu ini.
                        </div>
                    @endforelse
                </section>

                @if ($booking->verified_at || $booking->verification_note)
                    <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-7">
                        <h3 class="font-bold text-slate-800 mb-3">Hasil Verifikasi</h3>
                        <p class="text-sm text-slate-600">
                            {{ $booking->verifier ? 'Diverifikasi oleh ' . $booking->verifier->name : 'Diproses otomatis oleh sistem' }}
                            @if ($booking->verified_at)
                                pada {{ $booking->verified_at->locale('id')->translatedFormat('d M Y, H:i') }}
                            @endif
                        </p>
                        @if ($booking->verification_note)
                            <div class="mt-3 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">{{ $booking->verification_note }}</div>
                        @endif
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                @if ($booking->status === \App\Enums\BookingStatus::Pending)
                    <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-6">
                        <h3 class="font-bold text-slate-800">Tindakan Verifikasi</h3>
                        <p class="text-xs text-slate-500 mt-1">Pastikan detail pengajuan sudah sesuai sebelum mengambil keputusan.</p>

                        <form method="POST" action="{{ route('pj.requests.approve', $booking) }}" class="mt-5 space-y-3">
                            @csrf
                            <label for="approve-note" class="block text-sm font-semibold text-slate-700">Catatan persetujuan <span class="font-normal text-slate-400">(opsional)</span></label>
                            <textarea id="approve-note" name="note" rows="3" maxlength="2000"
                                      class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600"
                                      placeholder="Contoh: Silakan gunakan ruangan sesuai jadwal.">{{ old('note') }}</textarea>
                            <button type="submit"
                                    onclick="return confirm('Setujui {{ $booking->code }}? Pengajuan aktif lain yang bentrok akan otomatis ditolak oleh sistem.')"
                                    class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-3 text-sm font-bold text-white transition">
                                Setujui Pengajuan
                            </button>
                        </form>

                        <div class="my-5 border-t border-slate-100"></div>

                        <form method="POST" action="{{ route('pj.requests.reject', $booking) }}" class="space-y-3">
                            @csrf
                            <label for="reject-note" class="block text-sm font-semibold text-slate-700">Alasan penolakan <span class="text-red-500">*</span></label>
                            <textarea id="reject-note" name="note" rows="4" maxlength="2000" required
                                      class="w-full rounded-lg border-slate-300 text-sm focus:border-red-600 focus:ring-red-600"
                                      placeholder="Tuliskan alasan penolakan yang dapat dipahami peminjam.">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <button type="submit"
                                    onclick="return confirm('Tolak {{ $booking->code }}? Pastikan alasan penolakan sudah benar.')"
                                    class="w-full rounded-lg bg-red-50 hover:bg-red-100 px-4 py-3 text-sm font-bold text-red-700 transition">
                                Tolak Pengajuan
                            </button>
                        </form>
                    </section>
                @elseif ($booking->status === \App\Enums\BookingStatus::Queued)
                    <section class="bg-orange-50 border border-orange-100 rounded-xl p-5 md:p-6">
                        <h3 class="font-bold text-orange-800">Masih Dalam Antrean</h3>
                        <p class="text-sm text-orange-700 mt-2">
                            Pengajuan ini belum dapat diverifikasi karena ada pengajuan lain yang lebih dulu menunggu pada slot yang bertabrakan.
                        </p>
                        <a href="{{ route('pj.requests.index', ['status' => 'queued']) }}"
                           class="inline-block mt-4 text-sm font-semibold text-orange-800 hover:underline">Lihat antrean &rarr;</a>
                    </section>
                @else
                    <section class="bg-slate-50 border border-slate-200 rounded-xl p-5 md:p-6">
                        <h3 class="font-bold text-slate-800">Pengajuan Sudah Diproses</h3>
                        <p class="text-sm text-slate-600 mt-2">Tidak ada tindakan yang dapat dilakukan lagi pada pengajuan ini.</p>
                    </section>
                @endif

                <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Informasi Ruangan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Nama</p>
                            <p class="font-semibold text-slate-800 mt-1">{{ $booking->room->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tipe</p>
                            <p class="font-medium text-slate-800 mt-1">{{ ucfirst($booking->room->type) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Kapasitas</p>
                            <p class="font-medium text-slate-800 mt-1">{{ $booking->room->capacity }} orang</p>
                        </div>
                        @if (is_array($booking->room->facilities) && count($booking->room->facilities))
                            <div>
                                <p class="text-xs text-slate-500 mb-2">Fasilitas</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($booking->room->facilities as $facility)
                                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">{{ $facility }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            </aside>
        </div>

        <a href="{{ route('pj.requests.index') }}" class="inline-flex rounded-lg bg-slate-100 hover:bg-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition">&larr; Kembali ke Pengajuan</a>
    </div>
</x-app-layout>
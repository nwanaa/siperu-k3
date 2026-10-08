<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPeminjam() ?? false;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', Rule::exists('rooms', 'id')->where('is_active', true)->whereNull('deleted_at')],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'participants' => ['required', 'integer', 'min:1'],
            'purpose' => ['required', 'string', 'max:500'],
            'letter' => ['required', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
        ];
    }

    /** Aturan yang butuh lebih dari satu field. Jalan hanya jika aturan dasar lolos. */
    public function after(): array
    {
        return [function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $open = config('siperu.open_time');
            $close = config('siperu.close_time');

            if ($this->start_time < $open || $this->end_time > $close) {
                $validator->errors()->add('start_time', "Peminjaman hanya bisa dilakukan pukul {$open} - {$close}.");
            }

            if ($this->date === today()->toDateString() && $this->start_time <= now()->format('H:i')) {
                $validator->errors()->add('start_time', 'Jam mulai sudah lewat. Pilih jam yang belum berlalu.');
            }

            $room = Room::find($this->room_id);
            if ($room && (int) $this->participants > $room->capacity) {
                $validator->errors()->add('participants', "Jumlah peserta melebihi kapasitas ruangan ({$room->capacity} orang).");
            }
        }];
    }

    public function messages(): array
    {
        return [
            'letter.required' => 'Surat izin wajib diunggah.',
            'letter.mimes' => 'Surat izin harus berformat PDF, JPG, atau JPEG.',
            'letter.max' => 'Ukuran file terlalu besar (maksimal 2 MB).',
            'letter.uploaded' => 'Ukuran file terlalu besar (maksimal 2 MB).',
            'date.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ];
    }

    public function attributes(): array
    {
        return [
            'room_id' => 'ruangan',
            'date' => 'tanggal',
            'start_time' => 'jam mulai',
            'end_time' => 'jam selesai',
            'participants' => 'jumlah peserta',
            'purpose' => 'keperluan',
            'letter' => 'surat izin',
        ];
    }
}

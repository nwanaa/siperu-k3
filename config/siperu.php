<?php

return [
    // Jam operasional peminjaman. Ubah lewat .env tanpa menyentuh kode.
    'open_time' => env('SIPERU_OPEN', '07:00'),
    'close_time' => env('SIPERU_CLOSE', '21:00'),
];

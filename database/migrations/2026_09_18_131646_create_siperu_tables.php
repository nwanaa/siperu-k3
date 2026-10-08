<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('building')->nullable();
            $table->enum('type', ['kelas', 'laboratorium']);
            $table->unsignedSmallInteger('capacity');
            $table->json('facilities')->nullable();            // ["Proyektor","AC","Papan tulis"]
            $table->foreignId('pj_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['available', 'in_use', 'disabled'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('participants');
            $table->string('purpose');
            $table->string('letter_path')->nullable();          // surat izin (pdf/jpg, maks 2MB)
            $table->enum('status', ['pending', 'queued', 'approved', 'rejected', 'cancelled'])
                  ->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_note')->nullable();
            $table->timestamps();

            $table->index(['room_id', 'date', 'status']);       // query kalender dan cek bentrok
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('rooms');
    }
};
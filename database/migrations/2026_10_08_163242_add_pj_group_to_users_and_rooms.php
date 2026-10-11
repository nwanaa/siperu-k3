<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pj_group')->nullable()->after('role');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->string('pj_group')->nullable()->after('type');

            $table->dropForeign(['pj_user_id']);
            $table->dropColumn('pj_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('pj_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dropColumn('pj_group');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pj_group');
        });
    }
};
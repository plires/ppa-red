<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('activated_at')->nullable()->after('email_verified_at');
        });

        // Los usuarios existentes ya venían operando con contraseña puesta por el
        // admin; no deben quedar retroactivamente marcados como "pendientes de
        // activación". Solo los partners creados a partir de ahora empiezan null.
        DB::table('users')->whereNull('activated_at')->update(['activated_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activated_at');
        });
    }
};

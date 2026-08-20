<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'password')) {
                $table->string('password')->after('email');
            }
            if (!Schema::hasColumn('companies', 'remember_token')) {
                $table->rememberToken();
            }
            if (!Schema::hasColumn('companies', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['remember_token', 'email_verified_at']);
        });
    }
};
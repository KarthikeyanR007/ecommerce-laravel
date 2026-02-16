<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rh_customindividuallogs', function (Blueprint $table) {
            $table->json('cil_from_value')->nullable()->after('cil_action');
            $table->json('cil_to_value')->nullable()->after('cil_from_value');
            $table->index('cil_action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rh_customindividuallogs', function (Blueprint $table) {
            $table->dropColumn(['cil_from_value', 'cil_to_value']);
        });
    }
};

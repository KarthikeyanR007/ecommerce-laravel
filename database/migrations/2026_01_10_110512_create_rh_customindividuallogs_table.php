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
        Schema::create('rh_customindividuallogs', function (Blueprint $table) {
           $table->bigIncrements('cil_id');
            $table->unsignedBigInteger('cil_user_id');
            $table->string('cil_modal_path');
            $table->string('cil_modal_name');
            $table->string('cil_action');
            $table->timestamp('cil_created_at')->useCurrent();
            $table->unsignedBigInteger('cil_created_by');
            $table->timestamp('cil_updated_at')->nullable();
            $table->unsignedBigInteger('cil_updated_by')->nullable();

            $table->index('cil_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rh_customindividuallogs');
    }
};
//  cil_user_id
//  cil_modal_path
//  cil_modal_name
//  cil_action
//  cil_created_at
//  cil_created_by
//  cil_updated_at
//  cil_updated_by
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function ($table) {
            $table->text('description')->nullable()->change();
            $table->text('card_description')->nullable()->change();
            $table->text('pros')->nullable()->change();
            $table->text('cons')->nullable()->change();
            $table->text('hardware_considerations')->nullable()->change();
            $table->text('software_considerations')->nullable()->change();
            $table->text('wiring_instructions')->nullable()->change();
            $table->text('example_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //reverse up
        Schema::table('items', function ($table) {
            $table->text('description')->change();
            $table->text('card_description')->change();
            $table->text('pros')->change();
            $table->text('cons')->change();
            $table->text('hardware_considerations')->change();
            $table->text('software_considerations')->change();
            $table->text('wiring_instructions')->change();
            $table->text('example_code')->change();
        });
    }
};

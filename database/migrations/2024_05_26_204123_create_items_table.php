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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('photo')->nullable();
            $table->string('wiring_instructions');
            $table->string('wiring_photo')->nullable();
            $table->text('description');
            $table->text('pros');
            $table->text('cons');
            $table->text('hardware_considerations');
            $table->text('software_considerations');
            $table->text('example_code');
            $table->boolean('is_actuator')->default(false);
            $table->json('json_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

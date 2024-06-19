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
        Schema::dropIfExists('attribute_processor');
        Schema::dropIfExists('processors');
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('is_actuator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('attribute_processor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained();
            $table->foreignId('controller_id')->constrained();
            $table->timestamps();
        });

        Schema::create('processors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->boolean('is_actuator')->default(false);
        });
    }
};

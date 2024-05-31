<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        //add new pivot table called attribute_items
        Schema::create('attribute_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id');
            $table->foreignId('item_id');
            $table->timestamps();
        });

        //add a new pivot table called attribute_controllers
        Schema::create('attribute_controllers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id');
            $table->foreignId('controller_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_items');
        Schema::dropIfExists('attribute_controllers');
    }
};

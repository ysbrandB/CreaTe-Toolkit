<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('attribute_type_id');
            $table->timestamps();
        });

        //add new pivot table called attribute_items
        Schema::create('attribute_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id');
            $table->foreignId('item_id');
            $table->timestamps();
        });

        //add a new pivot table called attribute_controllers
        Schema::create('attribute_processor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id');
            $table->foreignId('controller_id');
            $table->timestamps();
        });

        Schema::create('attribute_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('color');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_item');
        Schema::dropIfExists('attribute_processor');
        Schema::dropIfExists('attribute_types');
    }
};

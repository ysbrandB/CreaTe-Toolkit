<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('edges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_item_id');
            $table->foreignId('to_item_id');
            $table->foreignId('belongsto_item_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edges');
    }
};

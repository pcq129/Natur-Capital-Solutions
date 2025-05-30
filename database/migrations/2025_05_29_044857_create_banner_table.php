<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // drop existing db table for banners to make new one
        Schema::dropIfExists('banners');

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->text('image');
            $table->string('banner_link', 255);
            $table->text('overlay_heading');
            $table->text('overlay_text');
            $table->text('buttons')->nullable();
            $table->text('links')->nullable();
            $table->integer('priority');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};

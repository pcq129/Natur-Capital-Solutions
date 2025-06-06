<?php

use App\Enums\Language;
use App\Enums\Status;
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

        Schema::dropIfExists('cms_pages');

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('content')->nullable();
            $table->enum('language', array_column(Language::cases(), 'value'));
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms');
    }
};

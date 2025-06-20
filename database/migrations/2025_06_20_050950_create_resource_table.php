<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ResourceType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('resources');
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->morphs('resourecable');
            $table->enum('resource_type', array_column(ResourceType::cases(), 'value'))->nullable();
            $table->tinyInteger('priority');
            $table->text('resource');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource');
    }
};

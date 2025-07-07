<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ContactType;
use App\Enums\ActionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('contact_details');
        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('title', 80);
            $table->string('description', 255);
            $table->enum('contact_type', array_column(ContactType::cases(), 'value'));
            $table->tinyInteger('status');
            $table->enum('action_type', array_column(ActionType::cases(), 'value'));
            $table->string('action_url', 255);
            $table->integer('priority', false, true);
            // $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

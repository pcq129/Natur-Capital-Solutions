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
        Schema::dropIfExists('email_templates');
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name', 80)->unique();
            $table->string('subject', 40);
            $table->longText('content');
            $table->string('language', 20);
            $table->unsignedBigInteger('send_to')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('email_templates');

    }
};

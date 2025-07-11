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

        Schema::dropIfExists('branch_offices');

        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('address', 255);
            $table->string('email', 80);
            $table->string('mobile', 20);
            $table->tinyInteger('status');
            $table->string('location', 30);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_offices');
    }
};

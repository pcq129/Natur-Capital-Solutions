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
        Schema::create('product_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Name of the section');
            $table->text('content')->nullable()->comment('Content of the section');
            $table->string('type', 50)->default('text')->comment('Type of the section, e.g., text, image, video');
            $table->integer('priority')->default(0)->comment('Order of the section in the product page');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('Foreign key to products table');
            // $table->unique(['product_id', 'section_name'], 'unique_product_section');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sections');
    }
};

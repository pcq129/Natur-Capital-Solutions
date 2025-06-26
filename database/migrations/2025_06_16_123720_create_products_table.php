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
        // Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->text('description')->nullable()->comment('Product description');
            $table->integer('minimum_quantity', false, true);
            $table->boolean('is_featured')->comment('for displaying products in featured/popular section');
            // $table->enum('status', array_column(Status::cases(), 'value'))->comment("-1 = deleted, 0 = inactive, 1 = active");
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('products');
    }
};

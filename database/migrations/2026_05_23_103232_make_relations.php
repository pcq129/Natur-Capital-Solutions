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
        // Schema::table('products', function (Blueprint $table) {
        //     $table->foreign('category_id')
        //         ->references('id')
        //         ->on('categories')
        //         ->onDelete('cascade');
        //     $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
        // });

        // Schema::table('cart_items', function (Blueprint $table) {
        //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // });

        // TODO
        // this requires morph relationship, not yet confident so will do it on monday
        // Schema::table('documents',function (Blueprint $table) {
        //     $table->foreign('')->references('id')->on('')->onDelete('cascade');
        //     $table->foreign('')->references('id')->on('')->onDelete('cascade');

        // });

        // TODO
        // this requires morph relationship, not yet confident so will do it on monday
        // Schema::table('resources',function (Blueprint $table) {});


        Schema::table('scheduled_services', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });


        Schema::table('warranties', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });

        Schema::table('invoices', function (Blueprint $table) {});

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::table('email_templates', function (Blueprint $table) {
            // $table->foreign('access_to')->references('id')->on('roles')->onDelete('cascade');
            // role authorization removed as instructed.
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });


        // Schema::table('services', function (Blueprint $table) {
        //     $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['sub_category_id']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
        });

        // TODO
        // this requires morph relationship, not yet confident so will do it on monday
        // Schema::table('documents',function (Blueprint $table) {
        //     $table->foreign('')->references('id')->on('')->onDelete('cascade');
        //     $table->foreign('')->references('id')->on('')->onDelete('cascade');

        // });

        // TODO
        // this requires morph relationship, not yet confident so will do it on monday
        // Schema::table('resources',function (Blueprint $table) {});


        Schema::table('scheduled_services', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });


        Schema::table('warranties', function (Blueprint $table) {
            $table->dropForeign(['warranty_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {});

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['order_id']);

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropForeign(['only_for']);
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['country_id']);
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });


        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
    }
};

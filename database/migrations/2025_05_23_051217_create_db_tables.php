<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;
use App\Enums\DeliveryMode;
use App\Enums\PaymentMode;
use App\Enums\ContentType;
use App\Enums\Language;
use App\Enums\DeliverMode;



return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->integer('minimum_quantity', false, true);
            $table->boolean('is_featured')->comment('for displaying products in featured/popular section');
            $table->tinyInteger('status')->comment("-1 = deleted, 0 = inactive, 1 = active");
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('is_featured')->comment('0 = not featured, 1 = featured');
            $table->tinyInteger('status')->comment('0 = inactive, 1 = active');
        });

        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('office', 255);
            $table->string('email', 80);
            $table->string('mobile');
            $table->tinyInteger('status')->comment('0 = inactive, 1 = active');
            $table->string('location');
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 140);
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 140);
            $table->unsignedBigInteger('country_id');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 140);
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('country_id');
        });

        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('address_label', 140);
            $table->string('address_line_1', 255);
            $table->string('street', 140);
            $table->integer('pincode', false, true);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('label');
        });

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('data');
            $table->string('language', 20);
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name', 80);
            $table->string('subject', 40);
            $table->longText('content');
            $table->string('language', 20);
            $table->unsignedBigInteger('access_to');
        });

        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('city_id');
            $table->text('message');
            $table->text('response');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('order_receipt');
            $table->enum('order_status', array_column(OrderStatus::cases(), 'value'));
            $table->unsignedBigInteger('invoice_id');
            $table->enum('delivery_mode', array_column(DeliveryMode::cases(), 'value'));
            $table->string('feedback', 255);
            $table->unsignedBigInteger('product_id');
            $table->enum('payment_mode', array_column(PaymentMode::cases(), 'value'));
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->json('invoice_data');
        });


        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('is_renewable')->comment('1 = renewable/extendible, 0 = non-renewable(fix tenure) ');
            $table->tinyInteger('is_renewed')->comment('1 = entry is a renewed warranty, 0 = entry is regular warranty');
            $table->unsignedBigInteger('previous_warranty');
        });


        Schema::create('scheduled_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('first_service');
            $table->date('second_service');
            $table->date('third_service');
            $table->tinyInteger('status')->comment(
                "
                    0 = not serviced,
                    1 = first_service_completed,
                    2 = second_service_completed,
                    3 = third_service_completed,
                    -1 = problem_with_first_service,
                    -2 = problem_with_second_service,
                    -1 = problem_with_third_service
                "
            );
        });

        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->text('image');
            $table->text('buttons');
            $table->text('links');
        });

        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('purpose', 80);
            $table->string('contact', 60);
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('subject_type');
            $table->enum('content_type', array_column(ContentType::cases(), 'value'));
            $table->tinyInteger('priority');
            $table->text('path');
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // this will create two columns (subject_id and subject_type)
            // supply classname to subject_type (service or product)
            // and respective id to identify the related entity.
            $table->morphs('subject');

            $table->tinyInteger('priority');
            $table->enum('content_type', array_column(ContentType::cases(), 'value'));
            $table->enum('language', array_column(LANGUAGE::cases(), 'value'));
            $table->longText('data');
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedBigInteger('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('sub_category');
        Schema::dropIfExists('category');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('contact_details');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('scheduled_services');
        Schema::dropIfExists('warranties');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('enquiries');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('branch_offices');
        Schema::dropIfExists('services');
        Schema::dropIfExists('products');
    }
};

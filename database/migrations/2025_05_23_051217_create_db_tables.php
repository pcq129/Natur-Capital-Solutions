<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;
use App\Enums\DeliveryMode;
use App\Enums\PaymentMode;
use App\Enums\ContentType;
use App\Enums\Language;
use App\Enums\ServiceStatus;
use App\Enums\Status;



return new class extends Migration
{

    // INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `company`, `country`, `city`, `provider`, `provider_token`, `token_expiration`, `role`, `status`, `deleted_at`) VALUES (NULL, 'Harmit', 'harmitkatariya153@gmail.com', NULL, '$2y$12$Cs79vJdZkFseHrJ4rEibJuAV3LB.UP0XzR8Y6jBa.9YxqE8jsI1j6', NULL, '2025-05-28 04:00:13', '2025-05-28 04:00:13', NULL, 'Natur Capital Solutions', NULL, NULL, NULL, NULL, NULL, 'admin', '1', NULL)
    // pass #12341234

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
            $table->enum('status', array_column(Status::cases(), 'value'))->comment("-1 = deleted, 0 = inactive, 1 = active");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedBigInteger('product_id');
            $table->tinyInteger('is_featured')->comment('0 = not featured, 1 = featured');
            $table->enum('status', array_column(Status::cases(), 'value'))->comment("-1 = deleted, 0 = inactive, 1 = active");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('office', 255);
            $table->string('email', 80);
            $table->string('mobile');
            $table->enum('status', array_column(Status::cases(), 'value'))->comment("-1 = deleted, 0 = inactive, 1 = active");
            $table->string('location');
            $table->timestamps();
            $table->softDeletes();
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
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('data');
            $table->string('language', 20);
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name', 80);
            $table->string('subject', 40);
            $table->longText('content');
            $table->string('language', 20);
            $table->unsignedBigInteger('access_to');
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
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
            $table->timestamps();
            $table->softDeletes();
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
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->json('invoice_data');
            $table->softDeletes();
        });


        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('is_renewable')->comment('1 = renewable/extendible, 0 = non-renewable(fix tenure) ');
            $table->tinyInteger('is_renewed')->comment('1 = entry is a renewed warranty, 0 = entry is regular warranty');
            $table->unsignedBigInteger('previous_warranty');
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->softDeletes();
        });


        Schema::create('scheduled_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->date('first_service');
            $table->date('second_service');
            $table->date('third_service');
            $table->enum('status', array_column(ServiceStatus::cases(), 'value'))->comment(
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
            $table->softDeletes();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->text('image');
            $table->text('overlay_heading');
            $table->text('overlay_sub_text');
            $table->text('buttons');
            $table->text('links');
            $table->integer('order');
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->string('purpose', 80);
            $table->string('contact', 60);
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('subject_type');
            $table->enum('content_type', array_column(ContentType::cases(), 'value'));
            $table->tinyInteger('priority');
            $table->text('path');
            $table->timestamps();
            $table->softDeletes();
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
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->enum('status', array_column(Status::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedBigInteger('category_id');
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
        Schema::dropIfExists('sub_category');
        Schema::dropIfExists('category');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('contact_details');
        Schema::dropIfExists('banners');
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

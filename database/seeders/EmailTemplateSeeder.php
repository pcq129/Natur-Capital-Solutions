<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $templates = [
            [
                'template_name' => 'order_placed',
                'subject' => 'Order Confirmation - #{{ order_id }}',
                'content' => <<<BLADE
<p>Hi {{ customer_name }},</p>
<p>Thank you for your order #{{ order_id }} placed on {{ order_date }}.</p>
<p>Here are your items:</p>
<ul>
@foreach (\$items as \$item)
    <li>{{ \$item['name'] }} (x{{ \$item['quantity'] }}) - ₹{{ \$item['price'] }}</li>
@endforeach
</ul>
<p>Total: ₹{{ order_total }}</p>
<p>We appreciate your business!</p>
BLADE,
                'language' => 'en',
                'send_to' => 1,
                'status' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_name' => 'password_reset',
                'subject' => 'Reset your password',
                'content' => <<<BLADE
<p>Hello {{ user_name }},</p>
<p>Click the link below to reset your password:</p>
<p><a href="{{ reset_link }}">Reset Password</a></p>
<p>If you did not request a password reset, you can ignore this email.</p>
BLADE,
                'language' => 'en',
                'send_to' => 1,
                'status' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_name' => 'account_verification',
                'subject' => 'Verify your account',
                'content' => <<<BLADE
<p>Hi {{ user_name }},</p>
<p>Welcome! Please verify your account by clicking the link below:</p>
<p><a href="{{ verification_link }}">Verify Account</a></p>
BLADE,
                'language' => 'en',
                'send_to' => 1,
                'status' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_name' => 'order_shipped',
                'subject' => 'Your order #{{ order_id }} has shipped!',
                'content' => <<<BLADE
<p>Hi {{ customer_name }},</p>
<p>Your order #{{ order_id }} has been shipped and is on its way.</p>
<p>Tracking Number: {{ tracking_number }}</p>
<p><a href="{{ tracking_url }}">Track your shipment</a></p>
BLADE,
                'language' => 'en',
                'send_to' => 1,
                'status' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_name' => 'order_delivered',
                'subject' => 'Order Delivered - #{{ order_id }}',
                'content' => <<<BLADE
<p>Hi {{ customer_name }},</p>
<p>Your order #{{ order_id }} was delivered on {{ delivery_date }}.</p>
<p>We hope you enjoy your purchase!</p>
BLADE,
                'language' => 'en',
                'send_to' => 1,
                'status' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'template_name' => 'low_stock_alert',
                'subject' => 'Low Stock Alert: {{ product_name }}',
                'content' => <<<BLADE
<p>Admin,</p>
<p>The product "{{ product_name }}" is low in stock.</p>
<p>Remaining quantity: {{ quantity }}</p>
BLADE,
                'language' => 'en',
                'send_to' => 2,
                'status' => '1',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('email_templates')->insert($templates);
    }
}

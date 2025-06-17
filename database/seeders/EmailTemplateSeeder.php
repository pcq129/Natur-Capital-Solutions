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

        DB::table('email_templates')->insert([
            'name' => 'User Registered',
            'subject' => 'User Registration Successful',
            'language' => 'en',
            'status' => 1,
            'created_at' => Carbon::parse('2025-06-17 04:35:43'),
            'updated_at' => Carbon::parse('2025-06-17 04:35:43'),
            'deleted_at' => null,
        ]);

        DB::table('trix_rich_texts')->insert([
            'field' => 'EmailTemplateContent',
            'model_type' => 'App\\Models\\EmailTemplate',
            'model_id' => 1,
            'content' => <<<HTML
<div>Friends, followers and other connections</div><div>Information we collect about your friends, followers and other connections</div><div>We collect information about friends, followers, groups, accounts, Facebook Pages and other users and communities that you're connected to and interact with. This includes how you interact with them across our products and which ones you interact with the most.</div><div>Information we collect about contacts</div><div>We also collect your contacts' information, such as their name and email address or phone number, if you choose to upload or import it from a <a href="https://www.facebook.com/privacy/policy/?annotations[0]=Definition-Device&amp;subpage=1.subpage.2-FriendsFollowersAndOther">device</a>, such as by syncing an address book.</div><div>If you don't use Meta Products, or use them without an account, your information might still be collected. <a href="https://www.facebook.com/help/637205020878504">Learn more</a> about how Meta uses contact information uploaded by account holders.</div><div>Learn how to upload and delete contacts on <a href="https://www.facebook.com/help/561688620598358?helpref=related">Facebook</a> and <a href="https://www.facebook.com/help/messenger-app/838237596230667">Messenger</a>, or how to connect your device's contact list on <a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fhelp.instagram.com%2F195069860617299%3Fhelpref%3Drelated&amp;h=AT0B8UjDrSZWNEK8WDV0uI4SqW2W_T1h9bEOpeuKoNQ2OpyZrxVOkHUJaeKVkjMPjpjLCwHGD451MDW_akCfzz-jhv21fb3P0hPS9cN4P_JuVZ0uUvjOhwWwREakx_GHNb8VsxaoNijotODzg54DAA">Instagram</a>.</div><div>Information we collect or infer about you based on others' activity</div><div>We collect information about you based on others' activity. <a href="https://www.facebook.com/privacy/policy/?annotations[0]=1.ex.20-WhenWeCollectInformation&amp;subpage=1.subpage.2-FriendsFollowersAndOther">See some examples</a>.<br><br></div><div>We also infer things about you based on others' activity. For example:</div><div><br></div><ul><li>We may suggest a friend to you through Facebook's People you may know feature if you both appear on a contact list that someone uploads.</li><li>We take into account whether your friends belong to a group when we suggest you join it.</li></ul>
HTML,
            'created_at' => Carbon::parse('2025-06-17 04:35:43'),
            'updated_at' => Carbon::parse('2025-06-17 04:35:43'),
        ]);

        //
        //         $templates = [
        //             [
        //                 'template_name' => 'order_placed',
        //                 'subject' => 'Order Confirmation - #{{ order_id }}',
        //                 'content' => <<<BLADE
        // <p>Hi {{ customer_name }},</p>
        // <p>Thank you for your order #{{ order_id }} placed on {{ order_date }}.</p>
        // <p>Here are your items:</p>
        // <ul>
        // @foreach (\$items as \$item)
        //     <li>{{ \$item['name'] }} (x{{ \$item['quantity'] }}) - ₹{{ \$item['price'] }}</li>
        // @endforeach
        // </ul>
        // <p>Total: ₹{{ order_total }}</p>
        // <p>We appreciate your business!</p>
        // BLADE,
        //                 'language' => 'en',
        //                 'send_to' => 1,
        //                 'status' => '1',
        //                 'created_at' => $now,
        //                 'updated_at' => $now,
        //             ],
        //             [
        //                 'template_name' => 'password_reset',
        //                 'subject' => 'Reset your password',
        //                 'content' => <<<BLADE
        // <p>Hello {{ user_name }},</p>
        // <p>Click the link below to reset your password:</p>
        // <p><a href="{{ reset_link }}">Reset Password</a></p>
        // <p>If you did not request a password reset, you can ignore this email.</p>
        // BLADE,
        //                 'language' => 'en',
        //                 'send_to' => 1,
        //                 'status' => '1',
        //                 'created_at' => $now,
        //                 'updated_at' => $now,
        //             ],
        //             [
        //                 'template_name' => 'account_verification',
        //                 'subject' => 'Verify your account',
        //                 'content' => <<<BLADE
        // <p>Hi {{ user_name }},</p>
        // <p>Welcome! Please verify your account by clicking the link below:</p>
        // <p><a href="{{ verification_link }}">Verify Account</a></p>
        // BLADE,
        //                 'language' => 'en',
        //                 'send_to' => 1,
        //                 'status' => '1',
        //                 'created_at' => $now,
        //                 'updated_at' => $now,
        //             ],
        //             [
        //                 'template_name' => 'order_shipped',
        //                 'subject' => 'Your order #{{ order_id }} has shipped!',
        //                 'content' => <<<BLADE
        // <p>Hi {{ customer_name }},</p>
        // <p>Your order #{{ order_id }} has been shipped and is on its way.</p>
        // <p>Tracking Number: {{ tracking_number }}</p>
        // <p><a href="{{ tracking_url }}">Track your shipment</a></p>
        // BLADE,
        //                 'language' => 'en',
        //                 'send_to' => 1,
        //                 'status' => '1',
        //                 'created_at' => $now,
        //                 'updated_at' => $now,
        //             ],
        //             [
        //                 'template_name' => 'order_delivered',
        //                 'subject' => 'Order Delivered - #{{ order_id }}',
        //                 'content' => <<<BLADE
        // <p>Hi {{ customer_name }},</p>
        // <p>Your order #{{ order_id }} was delivered on {{ delivery_date }}.</p>
        // <p>We hope you enjoy your purchase!</p>
        // BLADE,
        //                 'language' => 'en',
        //                 'send_to' => 1,
        //                 'status' => '1',
        //                 'created_at' => $now,
        //                 'updated_at' => $now,
        //             ],
        //             [
        //                 'template_name' => 'low_stock_alert',
        //                 'subject' => 'Low Stock Alert: {{ product_name }}',
        //                 'content' => <<<BLADE
        // <p>Admin,</p>
        // <p>The product "{{ product_name }}" is low in stock.</p>
        // <p>Remaining quantity: {{ quantity }}</p>
        // BLADE,
        //                 'language' => 'en',
        //                 'send_to' => 2,
        //                 'status' => '1',
        //                 'created_at' => $now,
        //                 'updated_at' => $now,
        //             ],
        //         ];

        //         DB::table('email_templates')->insert($templates);
    }
}

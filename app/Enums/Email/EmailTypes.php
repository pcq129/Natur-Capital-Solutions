<?php

namespace App\Enums\Email;


enum EmailTypes: string
{
    case USER_REGISTERED = 'user_registered';
    case USER_PASSWORD_RESET = 'user_password_reset';
    case USER_VERIFICATION = 'user_verification';
    case USER_WELCOME = 'user_welcome';
    case ADMIN_USER_CREATED = 'admin_user_created';
    case ADMIN_USER_UPDATED = 'admin_user_updated';
    case ADMIN_USER_DELETED = 'admin_user_deleted';




    // Email for order handling and notifications
    case ADMIN_ORDER_STATUS_UPDATED = 'admin_order_status_updated';

    case USER_ENQUIRY = 'user_enquiry';
    case ADMIN_ENQUIRY = 'admin_enquiry';
    case USER_ENQUIRY_RESPONSE = 'user_enquiry_response';

    case ADMIN_PAYMENT_RECEIVED = 'admin_payment_received';
    case USER_PAYMENT_RECEIVED = 'user_payment_received';

    case USER_ORDER_PLACED = 'user_order_placed';
    case ADMIN_ORDER_PLACED = 'admin_order_placed';

    case USER_ORDER_APPROVED = 'user_order_approved';
    case ADMIN_ORDER_APPROVED = 'admin_order_approved';
    case USER_ORDER_REJECTED = 'user_order_rejected';

    case ADMIN_SHIPMENT_DETAILS_ADDED = 'admin_shipment_details_added';
    case USER_SHIPMENT_DETAILS_ADDED = 'user_shipment_details_added';


    case USER_ORDER_CONFIRMED = 'user_order_confirmed';
    case USER_ORDER_CANCELLED = 'user_order_cancelled';
}

<?php

namespace App\Enums;

enum SystemMessage: string
{
    //  AUTH & ACCOUNT MESSAGES
    case REGISTRATION_SUCCESSFUL = 'Registration successful. Welcome aboard!';
    case REGISTRATION_FAILED = 'An error occurred during registration. Please try again later.';
    case WELCOME_EMAIL = 'Welcome to our store! We’re excited to have you with us.';
    case EMAIL_VERIFICATION = 'Please verify your email to complete your registration.';
    case PASSWORD_RESET_REQUEST = 'Here’s the link to reset your password.';
    case PASSWORD_CHANGED_SUCCESSFULLY = 'Your password has been changed successfully.';
    case PROFILE_UPDATED = 'Your profile information has been updated.';
    case ACCOUNT_DELETION_CONFIRMATION = 'Your account has been deleted. We’re sorry to see you go.';

    //  ORDER & PAYMENT MESSAGES
    case ORDER_CONFIRMATION = 'Your order has been confirmed.';
    case PAYMENT_SUCCESSFUL = 'Your payment was successful. Thank you!';
    case PAYMENT_FAILED = 'Payment failed. Please try again or use a different method.';
    case ORDER_SHIPPED = 'Your order has been shipped and is on its way!';
    case ORDER_DELIVERED = 'Your order has been delivered successfully.';
    case ORDER_CANCELLED = 'Your order has been cancelled as per your request.';
    case ORDER_REFUND_INITIATED = 'We’ve initiated your refund. It will be processed shortly.';
    case ORDER_REFUND_APPROVED = 'Your refund has been approved and is being processed.';
    case ORDER_REFUND_REJECTED = 'Your refund request was rejected. Please contact support.';

    //  MARKETING & CUSTOMER ENGAGEMENT
    case ABANDONED_CART = 'You left items in your cart. Complete your purchase now!';
    case BACK_IN_STOCK = 'Good news! An item you wanted is back in stock.';
    case PROMOTIONAL_OFFER = 'Special offer just for you! Don’t miss out.';
    case REVIEW_REQUEST = 'Tell us what you think about your recent purchase.';

    //  SUPPORT & COMMUNICATION
    case SUPPORT_TICKET_UPDATE = 'Your support ticket has been updated. Please check for the latest status.';

    //  ADMIN & SYSTEM ALERTS
    case LOW_STOCK_ALERT = 'Low stock alert: Some products are running out fast!';
    case HIGH_VALUE_ORDER = 'High-value order placed. Please review for accuracy.';
    case SELLER_PAYMENT_RELEASED = 'Your payment has been released successfully.';

    public function getMessage(): string
    {
        return $this->value;
    }
}

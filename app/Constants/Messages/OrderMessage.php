<?php
namespace App\Constants\Messages;

class OrderMessages
{
    public const ORDER_CONFIRMATION = 'Your order has been confirmed.';
    public const PAYMENT_SUCCESSFUL = 'Your payment was successful. Thank you!';
    public const PAYMENT_FAILED = 'Payment failed. Please try again or use a different method.';
    public const ORDER_SHIPPED = 'Your order has been shipped and is on its way!';
    public const ORDER_DELIVERED = 'Your order has been delivered successfully.';
    public const ORDER_CANCELLED = 'Your order has been cancelled as per your request.';
    public const ORDER_REFUND_INITIATED = 'We’ve initiated your refund. It will be processed shortly.';
    public const ORDER_REFUND_APPROVED = 'Your refund has been approved and is being processed.';
    public const ORDER_REFUND_REJECTED = 'Your refund request was rejected. Please contact support.';
}

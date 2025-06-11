<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PLACED = 'placed';
    case SHIPMENT_DETAILS_PENDING = 'shipment-details-pending';
    case PAYMENT_PENDING = 'payment-pending';
    case PAYMENT_PROCESSING = 'payment-processing';
    case PAYMENT_FAILED = 'payment-failed';
    case APPROVAL_PENDING = 'approval-pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case SHIPPED = 'shipped';
    case IN_TRANSIT = 'in-transit';
    case OUT_FOR_DELIVERY = 'out-for-delivery';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case VALIDATION_FAILED = 'validation-failed';
    case AWAITING_STOCK = 'awaiting-stock';
    case CANCELLED_BY_USER = 'cancelled-by-user';
    case CANCELLED_BY_ADMIN = 'cancelled-by-admin';
    case AUTO_CANCELLED = 'auto-cancelled';
    case RETURN_REQUESTED = 'return-requested';
    case RETURN_APPROVED = 'return-approved';
    case RETURN_RECEIVED = 'return-received';
    case RETURN_INITIATED = 'return-initiated';
    case REFUND_COMPLETED = 'refund-completed';
    case ON_HOLD = 'on-hold';
    case DELIVERY_FAILED = 'delivery-failed';
    case LOST_IN_TRANSIT = 'lost-in-transit';
    case DAMAGED_IN_TRANSIT = 'damaged-in-transit';
    case REPLACEMENT_SHIPPED = 'replacement-shipped';
    case AWAITING_CUSTOMER_RESPONSE = 'awaiting-customer-response';
    case PRE_ORDER = 'pre-order';

    // public function label(): string
    // {
    //     return match ($this) {
    //         self::PLACED => 'Placed',
    //         self::SHIPMENT_DETAILS_PENDING => 'Shipment Details Pending',
    //         self::PAYMENT_PENDING => 'Payment Pending',
    //         self::PAYMENT_PROCESSING => 'Payment Processing',
    //         self::PAYMENT_FAILED => 'Payment Failed',
    //         self::APPROVAL_PENDING => 'Approval Pending',
    //         self::APPROVED => 'Approved',
    //         self::REJECTED => 'Rejected',
    //         self::SHIPPED => 'Shipped',
    //         self::IN_TRANSIT => 'In Transit',
    //         self::OUT_FOR_DELIVERY => 'Out for Delivery',
    //         self::DELIVERED => 'Delivered',
    //         self::COMPLETED => 'Completed',
    //         self::VALIDATION_FAILED => 'Validation Failed',
    //         self::AWAITING_STOCK => 'Awaiting Stock',
    //         self::CANCELLED_BY_USER => 'Cancelled by User',
    //         self::CANCELLED_BY_ADMIN => 'Cancelled by Admin',
    //         self::AUTO_CANCELLED => 'Auto Cancelled',
    //         self::RETURN_REQUESTED => 'Return Requested',
    //         self::RETURN_APPROVED => 'Return Approved',
    //         self::RETURN_RECEIVED => 'Return Received',
    //         self::RETURN_INITIATED => 'Return Initiated',
    //         self::REFUND_COMPLETED => 'Refund Completed',
    //         self::ON_HOLD => 'On Hold',
    //         self::DELIVERY_FAILED => 'Delivery Failed',
    //         self::LOST_IN_TRANSIT => 'Lost in Transit',
    //         self::DAMAGED_IN_TRANSIT => 'Damaged in Transit',
    //         self::REPLACEMENT_SHIPPED => 'Replacement Shipped',
    //         self::AWAITING_CUSTOMER_RESPONSE => 'Awaiting Customer Response',
    //         self::PRE_ORDER => 'Pre-Order',
    //     };
    // }
}

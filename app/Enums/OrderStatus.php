<?php

namespace App\Enums;

enum OrderStatus:string
{
    case Placed = 'placed';
    case ShipmentDetailsPending = 'shipment-details-pending';
    case PaymentPending = 'payment-pending';
    case PaymentProcessing = 'payment-processing';
    case PaymentFailed = 'payment-failed';
    case ApprovalPending = 'approval-pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Shipped = 'shipped';
    case InTransit = 'in-transit';
    case OutForDelivery = 'out-for-delivery';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case ValidationFailed = 'validation-failed';
    case AwaitingStock = 'awaiting-stock';
    case CancelledByUser = 'cancelled-by-user';
    case CancelledByAdmin = 'cancelled-by-admin';
    case AutoCancelled = 'auto-cancelled';
    case ReturnRequested = 'return-requested';
    case ReturnApproved = 'return-approved';
    case ReturnReceived = 'return-received';
    case ReturnInitiated = 'return-initiated';
    case RefundCompleted = 'refund-completed';
    case OnHold = 'on-hold';
    case DeliveryFailed = 'deliver-failed';
    case LostInTransit = 'lost-in-transit';
    case DamagedInTransit = 'damaged-in-transit';
    case ReplacementShipped = 'replacement-shipped';
    case AwaitingCustomerResponse = 'awaiting-customer-response';
    case PreOrder = 'pre-order';
}




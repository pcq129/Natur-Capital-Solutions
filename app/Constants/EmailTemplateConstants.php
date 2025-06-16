<?php

namespace App\Constants;

class EmailTemplateConstants
{
    public const STORE_SUCCESS = 'Template created successfully';
    public const STORE_FAIL = 'Error while adding email template';
    public const FETCH_SUCCESS = 'Email templates fetched successfully';
    public const FETCH_FAIL = 'Error while fetching email templates';
    public const DELETE_SUCCESS = 'Email template deleted successfully';
    public const DELETE_FAIL = 'Error while deleting email template';
    public const UPDATE_SUCCESS = 'Template updated successfully';
    public const UPDATE_FAIL = 'Error while updating email template';
    public const NO_CHANGE = 'No changes detected';
    public const NOT_AUTHORIZED = 'Email template is not allowed for specified User';




    // email types
    public const REGISTRATION_SUCCESSFUL='User registered successfully';
    public const REGISTRATION_FAILED='Error occured, please try later';
    public const ORDER_CONFIRMATION = 'Order Confirmed';
    public const PAYMENT_SUCCESSFUL = 'Payment Successful';
    public const PAYMENT_FAILED = 'Payment Failed';
    public const ORDER_SHIPPED = '';
    public const ORDER_DELIVERED = '';
    public const ORDER_CANCELLED = '';
    public const ORDER_REFUND_INITIATED = '';
    public const ORDER_REFUND_APPROVED = '';
    public const ORDER_REFUND_REJECTED = '';


    public const WELCOME_EMAIL = '';
    public const EMAIL_VERIFICATION = '';
    public const PASSWORD_RESET_REQUEST = '';
    public const PASSWORD_CHANGED_SUCCESSFULLY = '';
    public const PROFILE_UPDATED = '';
    public const ACCOUNT_DELETION_CONFIRMATION = '';

    public const ABANDONED_CART = '';
    public const BACK_IN_STOCK = '';
    public const SELLER_PAYMENT_RELEASED = '';
    public const PROMOTIONAL_OFFER ='';
    public const REVIEW_REQUEST ='';
    public const SUPPORT_TICKET_UPDATE ='';


    public const LOW_STOCK_ALERT ='';
    public const HIGH_VALUE_ORDER ='';
}
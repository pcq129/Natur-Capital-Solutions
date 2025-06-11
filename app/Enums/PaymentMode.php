<?php

namespace App\Enums;

enum PaymentMode:string{
    case NET_BANKING = 'net-banking';
    case CASH_ON_DELIVERY = 'cash-on-delivery';
}
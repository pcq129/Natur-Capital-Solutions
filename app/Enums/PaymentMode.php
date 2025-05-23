<?php

namespace App\Enums;

enum PaymentMode:string{
    case NetBanking = 'net-banking';
    case CashOnDelivery = 'cash-on-delivery';
}
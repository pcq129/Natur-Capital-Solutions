<?php

namespace App\Enums;


enum DeliveryMode:string{
    case PICKUP = 'pickup';
    case DELIVERY = 'delivery';
}
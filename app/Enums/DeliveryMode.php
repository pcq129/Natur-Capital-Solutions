<?php

namespace App\Enums;


enum DeliveryMode:string{
    case Pickup = 'pickup';
    case Delivery = 'delivery';
}
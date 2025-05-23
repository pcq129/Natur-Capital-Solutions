<?php

namespace App\Enums;

enum Status:int
{
    case Active = 1;
    case Inactive = 0;
    case Deleted = -1;
}

<?php

namespace App\Enums;

enum ServiceStatus: int
{
    case NOT_SERVICED = 0;
    case FIRST_SERVICE_COMPLETED = 1;
    case SECOND_SERVICE_COMPLETED = 2;
    case THIRD_SERVICE_COMPLETED = 3;
    case PROBLEM_IN_FIRST_SERVICE = -1;
    case PROBLEM_IN_SECOND_SERVICE = -2;
    case PROBLEM_IN_THIRD_SERVICE = -3;
}

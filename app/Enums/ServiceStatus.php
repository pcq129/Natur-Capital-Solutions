<?php

namespace App\Enums;

enum ServiceStatus: int
{
    case NotServiced = 0;
    case FirstServiceCompleted = 1;
    case SecondServiceCompleted = 2;
    case ThirdServiceCompleted = 3;
    case ProblemInFirstService = -1;
    case ProblemInSecondService = -2;
    case ProblemInThirdServic = -3;
}

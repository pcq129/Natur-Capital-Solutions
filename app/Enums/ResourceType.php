<?php

namespace App\Enums;

enum ResourceType: string {
    case IMAGE = 'image';
    case FILE = 'file';
    case PDF = 'pdf';
    case VIDEO = 'video';
    case TRIX = 'trix';
}

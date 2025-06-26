<?php

namespace App\Enums;

enum FileType: string {
    case IMAGE = 'image';
    case FILE = 'file';
    case PDF = 'pdf';
    case VIDEO = 'video';
    case TRIX = 'trix';
    case MAIN_IMAGE = 'main_image';
    case PRODUCT_DETAIL_IMAGE = 'product_detail_image';
}

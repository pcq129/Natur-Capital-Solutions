<?php

namespace App\Enums;

enum FileType: int {
    case IMAGE = '1';// 'image';
    case FILE = '2';//'file';
    case PDF = '3';//'pdf';
    case VIDEO = '4';//'video';
    case TRIX = '5';//'trix';
    case MAIN_IMAGE = '6';//'main_image';
    case PRODUCT_DETAIL_IMAGE = '7';//'product_detail_image';
}

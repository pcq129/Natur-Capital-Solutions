<?php

namespace App\Constants;


class ProductConstants
{
    // path constants (changing them will affect the code)
    public const PRODUCT_IMAGE_PATH = 'product/images';
    public const PRODUCT_FILE_PATH = 'product/files';
    public const PRODUCT_DESCRIPTION = 'Description';
    public const PRODUCT_INFORMATION = 'Information';
    public const PRODUCT_CHARACTERISTICS = 'Characteristics';
    public const PRODUCT_WARRANTY_LIST = 'WarrantyList';
    public const PRODUCT_SERVICE_LIST = 'ServiceList';

    // variable constants (changing them won't affect the code)
    public const STORE_SUCCESS = 'New Product added successfully';
    public const STORE_FAIL = 'Error while creating Product';
    public const FETCH_SUCCESS = 'Products fetched successfully';
    public const FETCH_FAIL = 'Error while fetching Products';
    public const UPDATE_SUCCESS = 'Product updated successfully';
    public const UPDATE_FAIL = 'Error while updating Product';
    public const DELETE_SUCCESS = 'Product deleted successfully';
    public const DELETE_FAIL = 'Failed to delete Product';
    public const NO_CHANGE = 'No changes detected';
    public const NOT_FOUND = 'Product not found';
    public const PRODUCT_DELETE_MODAL_ID = 'productDeleteModalId';
}

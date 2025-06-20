<?php

namespace App\Constants;


class SubCategoryConstants
{
    public const STORE_SUCCESS = 'New Sub-Category added successfully';
    public const STORE_FAIL = 'Error while creating Sub-Category';
    public const FETCH_SUCCESS = 'Categories fetched successfully';
    public const FETCH_FAIL = 'Error while fetching Categories';
    public const UPDATE_SUCCESS = 'Sub-Category updated successfully';
    public const UPDATE_FAIL = 'Error while updating Sub-Category';
    public const DELETE_SUCCESS = 'Sub-Category deleted successfully';
    public const DELETE_FAIL = 'Failed to delete Sub-Category';
    public const NO_CHANGE = 'No changes detected';
    public const NOT_FOUND = 'Sub-Category not found';
    public const DELETE_SUB_CATEGORY_MODAL = 'deleteSub-CategoryModal';
    public const UPDATE_SUB_CATEGORY_MODAL = 'updateSub-CategoryModal';
    public const STATUS_ERROR = 'error';
    public const UPDATE_ALREADY_EXISTS = 'Sub-Category with the same name already exists';
    public const ERROR_NAME_REGEX ='Name can only contain letters and spaces.' ;
    public const ERROR_NAME_STRING = 'Name must be a string';
    public const ERROR_NAME_UNIQUE = 'Sub-Category with the same name already exists';
    public const ERROR_NAME_REQUIRED = 'Sub-Category name is required';
    public const ERROR_NAME_MIN = 'Name should contain atleast 2 characters';
    public const ERROR_NAME_MAX ='Name should not exceed 80 characters';
    public const ERROR_STATUS_REQUIRED = 'Plese select a status for category';
    public const ERROR_STATUS_ENUM = 'Status value is invalid';
    public const ERROR_CATEGORY_ID_REQUIRED = 'Category ID is required';
    public const ERROR_CATEGORY_ID_NUMERIC = 'Category ID must be numeric';

}

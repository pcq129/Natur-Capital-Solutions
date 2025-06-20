<?php

namespace App\Constants;


class CategoryConstants
{
    public const STORE_SUCCESS = 'New Category added successfully';
    public const STORE_FAIL = 'Error while creating Category';
    public const FETCH_SUCCESS = 'Categories fetched successfully';
    public const FETCH_FAIL = 'Error while fetching Categories';
    public const UPDATE_SUCCESS = 'Category updated successfully';
    public const UPDATE_FAIL = 'Error while updating Category';
    public const DELETE_SUCCESS = 'Category deleted successfully';
    public const DELETE_FAIL = 'Failed to delete Category';
    public const NO_CHANGE = 'No changes detected';
    public const NOT_FOUND = 'Category not found';
    public const DELETE_CATEGORY_MODAL = 'deleteCategoryModal';
    public const UPDATE_CATEGORY_MODAL = 'updateCategoryModal';
    public const ERROR_NAME_REQUIRED = 'Category name is required';
    public const ERROR_NAME_STRING = 'Category name must be a string';
    public const ERROR_NAME_MAX = 'Category name must not exceed 80 characters';
    public const ERROR_NAME_MIN = 'Name should contain atleast 2 characters';
    public const ERROR_NAME_REGEX = 'Category name can only contain letter and spaces';
    public const ERROR_NAME_UNIQUE = 'Category with the same name already exists';
    public const ERROR_STATUS_REQUIRED = 'Plese select a status for category';
    public const ERROR_STATUS_ENUM = 'Status value is invalid';
}

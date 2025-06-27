@extends('layouts.app')

@php
    use App\Models\Product;
    use App\Enums\Status;
    use App\Constants\ProductConstants as CONSTANTS;
    // $categories = [
    //     'category1' => [
    //         'id' => 1,
    //         'name' => 'Electronics',
    //     ],
    //     'category2' => [
    //         'id' => 2,
    //         'name' => 'Home Appliances',
    //     ],
    // ];
    // $subCategories = [
    //     'subcategory1' => [
    //         'id' => 1,
    //         'name' => 'Mobile Phones',
    //     ],
    // ];
@endphp

@section('content_header')
    <div class="row mb-1 justify-content-between">
        <div class="col-sm-6">
            <h1><strong>Add Product</strong></h1>
        </div>
        <div>
        </div>
    </div>
@stop


@section('content')



    <div class="card">
        <div class="card-body p-4">
            <form data-validate id="createProductForm" action="{{ route('products.store') }}" method="POST">
                @csrf

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="true" id="isFeatured" name="isFeatured">
                    <label class="form-check-label" for="isFeatured">
                      Is Featured
                    </label>
                  </div>
                <div class="form-group">
                    <label for="productName">Product Name*</label>
                    <input type="text" class="form-control" id="productName" name="name" required>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label for="productCategory">Product Category*</label>
                        <select class="custom-select" id="productCategory" name="productCategory" required>
                            <option value=0>Select Category</option>
                            {{-- Assuming $categories is passed from the controller --}}
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="productSubCategory">Product Sub Category*</label>
                        <select class="custom-select" id="productSubCategory" name="productSubCategory" required>
                            <option value="0">Select Subcategory</option>

                            @foreach ($subCategories ?? [] as $subCategory)
                                <option value="{{ $subCategory['id'] }}">{{ $subCategory['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label for="minimumQuantity">Minimum Quantity</label>
                        <input type="number" class="form-control" id="minimumQuantity" name="minimumQuantity"
                            value="1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Description*</label>
                    @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_DESCRIPTION, ['hideButtonIcons' => ['attach']])
                    <div class="priority">
                        <select name="descriptionPriority" class="custom-select mt-1" required>
                            <option selected>Description Priority</option>
                            @for ($priority = 1; $priority <= 5; $priority++)
                                <option value="{{ $priority }}">{{ $priority }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">More Information*</label>
                    @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_INFORMATION, ['hideButtonIcons' => ['attach']])
                    <div class="priority">
                        <select name="informationPriority" class="custom-select mt-1" required>
                            <option selected>More Information Priority</option>
                            @for ($priority = 1; $priority <= 5; $priority++)
                                <option value="{{ $priority }}">{{ $priority }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Characteristics*</label>
                    @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_CHARACTERISTICS, ['hideButtonIcons' => ['attach']])
                    <div class="priority">
                        <select name="characteristicsPriority" class="custom-select mt-1" required>
                            <option selected>Characteristics Priority</option>
                            @for ($priority = 1; $priority <= 5; $priority++)
                                <option value="{{ $priority }}">{{ $priority }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Warranty List*</label>
                    @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_WARRANTY_LIST, ['hideButtonIcons' => ['attach']])
                    <div class="priority">
                        <select name="warrantyListPriority" class="custom-select mt-1" required>
                            <option selected>Warranty List Priority</option>
                            @for ($priority = 1; $priority <= 5; $priority++)
                                <option value="{{ $priority }}">{{ $priority }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Service List*</label>
                    @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_SERVICE_LIST, ['hideButtonIcons' => ['attach']])
                    <div class="priority">
                        <select name="serviceListPriority" class="custom-select mt-1" required>
                            <option selected>Service List Priority</option>
                            @for ($priority = 1; $priority <= 5; $priority++)
                                <option value="{{ $priority }}">{{ $priority }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary w-100" type="submit">Next</button>
            </form>
        </div>
    </div>
@stop

@push('js')
    @trixassets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script>
        $(document).ready(function() {

            $('#createProductForm').on('submit', function(e) {

                const priorities = Array.from(document.querySelectorAll('select[name$="Priority"]'))
                    .map(select => select.value)
                    .filter(val => val !== "");

                const hasDuplicates = new Set(priorities).size !== priorities.length;
                const allNumericStrings = priorities.every(v => /^\d+$/.test(v));


                if ($('#productCategory').val() == 0 || $('#productSubCategory').val() == 0) {
                    e.preventDefault();
                    toastr.error('Please select a category and subcategory.');
                }

                console.log($('#productCategory').val(), $('#productSubCategory').val());

                if (!allNumericStrings) {
                    e.preventDefault();
                    toastr.error('Please specify priorities for each section.');
                }
                if (hasDuplicates) {
                    e.preventDefault();
                    toastr.error('Priority values must be unique.');
                }

            });

            $('#productCategory').on('change', function() {
                console.log('Category changed:', $(this).val());
                const categoryId = $(this).val();
                const subSelect = $('#productSubCategory');
                subSelect.empty().append('<option value="">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: `/get-subcategories/${categoryId}`,
                        type: 'GET',
                        success: function(data) {
                            subSelect.empty().append(
                                '<option >Select Subcategory</option>');
                            $.each(data, function(index, sub) {
                                subSelect.append(
                                    `<option value="${sub.id}">${sub.name}</option>`
                                );
                            });
                        },
                        error: function() {
                            subSelect.empty().append(
                                '<option value="">Failed to load</option>');
                        }
                    });
                } else {
                    subSelect.empty().append('<option value=0>Select Sub Category</option>');
                }
            });
        });
    </script>
@endpush



@push('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        @media print {
            body {
                margin: 0 !important;
            }
        }

        .main-container {
            font-family: 'Lato';
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        .ck-content {
            font-family: 'Lato';
            line-height: 1.6;
            word-break: break-word;
        }

        .editor-container_classic-editor .editor-container__editor {
            min-width: 795px;
            max-width: 795px;
        }

        .editor-container_include-block-toolbar {
            margin-left: 42px;
        }
    </style>
@endpush

@extends('layouts.app')


@php
    use App\Models\Product;
    use App\Enums\Status;
    use App\Constants\ProductConstants as CONSTANTS;
    use App\Enums\FileType;
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
    <h1>Create Product</h1>
@stop

@section('content')

    {{-- @php
        dd($categories, $product)
    @endphp --}}
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item"><a class="nav-link active disabled" id="basic-tab" data-toggle="tab" href="#basic"
                        role="tab">1.
                        Basic Info</a></li>
                <li class="nav-item"><a class="nav-link disabled" id="images-tab" data-toggle="tab" href="#images"
                        role="tab">2.
                        Add Images</a></li>
                <li class="nav-item"><a class="nav-link disabled" id="files-tab" data-toggle="tab" href="#files"
                        role="tab">3.
                        Add Files</a></li>
            </ul>
        </div>
        <div class="card-body tab-content" id="productTabContent">

            {{-- Tab 1: Create Product --}}
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <form data-validate method="POST" id="productTextDataForm"
                    action="{{ route('products.update', $product[0]->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="true" id="isFeatured" name="isFeatured">
                        <label class="form-check-label " for="isFeatured">
                            Is Featured
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="productName">Product Name*</label>
                        <input type="text" value="{{ $product['name'] ?? old('name') }}" class="form-control"
                            id="productName" name="name" required>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label for="productCategory">Product Category*</label>
                            <select class="custom-select" id="productCategory" name="productCategory" required>
                                <option value='0'>Select Category</option>
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
                                value="{{ $product['minimum_quantity'] ?? 1 }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description*</label>
                        @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_DESCRIPTION, ['hideButtonIcons' => ['attach'], 'id' => CONSTANTS::PRODUCT_DESCRIPTION])
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
                        <label class="form-label">More Information*</label>
                        @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_INFORMATION, ['hideButtonIcons' => ['attach'], 'id' => CONSTANTS::PRODUCT_INFORMATION])
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
                        <label class="form-label">Characteristics*</label>
                        @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_CHARACTERISTICS, ['hideButtonIcons' => ['attach'], 'id' => CONSTANTS::PRODUCT_CHARACTERISTICS])
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
                        <label class="form-label">Warranty List*</label>
                        @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_WARRANTY_LIST, ['hideButtonIcons' => ['attach'], 'id' => CONSTANTS::PRODUCT_WARRANTY_LIST])
                        <div class="priority">
                            <select name="warrantylistPriority" class="custom-select mt-1" required>
                                <option selected>Warranty List Priority</option>
                                @for ($priority = 1; $priority <= 5; $priority++)
                                    <option value="{{ $priority }}">{{ $priority }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service List*</label>
                        @trix(\App\Models\Product::class, CONSTANTS::PRODUCT_SERVICE_LIST, ['hideButtonIcons' => ['attach'], 'id' => CONSTANTS::PRODUCT_SERVICE_LIST])
                        <div class="priority">
                            <select name="servicelistPriority" class="custom-select mt-1" required>
                                <option selected>Service List Priority</option>
                                @for ($priority = 1; $priority <= 5; $priority++)
                                    <option value="{{ $priority }}">{{ $priority }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary next-tab" id="productTextDataSubmit"
                        data-next="#images">Next</button>
                    {{-- <button class="btn btn-primary w-100" type="submit">Next</button> --}}
                </form>
            </div>

            {{-- Tab 2: Add Images --}}
            <div class="tab-pane fade" id="images" role="tabpanel">
                <form data-validate id="addProductImagesForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group productMainImage h-50">
                        <h5><strong>List-Page/Main Image</strong></h5>
                        <label class="border border-secondary px-3 rounded" for="productImage">Select Product
                            Image</label>
                        <input type="file" class="form-control border border-secondary" id="productImage"
                            name="productImage" accept=".jpg,.jpeg,.png">
                        <small id='warning' class="form-text text-danger" hidden></small>
                        <div class="image-preivew">
                            <div id="productPreviewImage" class="image-wrapper m-1 d-inline">
                            </div>
                        </div>
                    </div>



                    <div class="form-group productDetailImages h-50">
                        <h5><strong>Product Details Image</strong></h5>
                        <label class="border border-secondary px-3 rounded" for="productDetailImages">Select Extra
                            Images</label>
                        <input type="file" class="form-control border border-secondary" id="productDetailImages"
                            name="productDetailImages[]" accept=".jpg,.jpeg,.png" multiple>
                        <small class="form-text text-muted">You can upload multiple images (max size: 8 MB each).</small>
                        <div class="image-preivew">
                            <div id="productDetailPreviewImage" class="image-wrapper m-1 d-inline">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary prev-tab" id="imagesTabPreviousBtn"
                        data-prev="#basic">Back</button>
                    <button type="button" id="productImageSubmit" class="btn btn-primary next-tab"
                        data-next="#files">
                        <span class="spinner-border spinner-border-sm d-none" id="imageSubmitSpinner" role="status"
                        aria-hidden="true"></span>Next</button>
                    {{-- <button type="submit" class="btn btn-primary mt-3">Upload Images</button> --}}
                </form>
            </div>

            {{-- Tab 3: Add Files --}}
            <div class="tab-pane fade" id="files" role="tabpanel">
                <form data-validate id="addProductFiles" method="POST" enctype="multipart/form-data">

                    <div class="form-group productVideo h-50">
                        <h5><strong>Video Instruction</strong></h5>
                        <label class="border border-secondary px-3 rounded" for="videoInstruction">Select Video</label>
                        <input type="file" class="form-control border border-secondary" id="videoInstruction"
                            name="videoInstruction" accept=".mp4">
                        <small id='warning' class="form-text text-danger" hidden></small>
                    </div>

                    <div class="form-group productDocuments h-50">
                        <h5><strong>Documents/Downloads</strong></h5>
                        <label class="border border-secondary px-3 rounded" for="documentsInput">Select Files</label>
                        <input type="file" class="form-control border border-secondary" id="documentsInput"
                            name="files[]" accept=".pdf" multiple>
                        <small class="form-text text-muted">You can upload multiple documents (max size: 8 MB
                            each).</small>
                    </div>
                </form>
                <button type="button" class="btn btn-secondary prev-tab" id="filesTabPreviousBtn"
                    data-prev="#images">Back</button>
                <button type="submit" id="finalSubmit" class="btn btn-success">
                    <span class="spinner-border spinner-border-sm d-none" id="finalSubmitSpinner"
                    aria-hidden="true"></span>
                   Submit
                    </button>
            </div>

        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
@endpush

@push('js')
    @trixassets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            const productImageForm = document.getElementById('addProductImagesForm');
            const productFilesForm = document.getElementById('addProductFiles');
            const productTextsForm = document.getElementById('productTextDataForm');

            // code to set values to respective input fields;

            // trix fields
            function setValue(text = '') {
                const trixEditor = document.querySelector("trix-editor")
                trixEditor.editor.insertHTML(text);
            }

            const product = {!! json_encode($product) !!}[0];
            const sections = product.sections;
            const product_files = product.product_files;
            const productName = document.getElementById('productName');
            const productCategory = document.getElementById('productCategory');
            const productSubCategory = document.getElementById('productSubCategory');
            const productMinQty = document.getElementById('minimumQuantity');
            const previewImage = document.getElementById('productPreviewImage');
            const previewDetailImage = document.getElementById('productDetailPreviewImage');
            const BASE_URL = "{{ config('app.url') }}";
            const allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const allowedVideoTypes = ['video/mp4'];
            const allowedDocumentTypes = ['application/pdf'];
            const categoryChangeEvent = new Event('change', {
                bubbles: true
            });


            productName.value = product.name;
            productCategory.value = product.category_id;
            // Trigger change to load subcategories
            productMinQty.value = product.minimum_quantity;


            sections.forEach(section => {
                const input = document.getElementById(section.name);
                const LCSectionName = section.name.toLowerCase();
                const priorityName = LCSectionName + 'Priority';
                const priority = document.querySelector("select[name='" + priorityName + "']");
                console.log(priorityName);
                if (input) {
                    input.value = section.content;
                    priority.value = section.priority;

                    // Also update associated trix-editor if exists
                    const trixEditor = document.querySelector(`trix-editor[input="${input.id}"]`);
                    if (trixEditor) {
                        trixEditor.editor.loadHTML(section.content);
                    }
                }
            });

            product_files.forEach(product_file => {

                if (product_file.file_type == '{{ FileType::MAIN_IMAGE }}') {
                    const img = document.createElement('img');
                    img.src = BASE_URL + '/storage' + product_file.file_path;
                    img.classList.add('img-thumbnail', 'productImagePreview');
                    img.style.width = '200px';
                    previewImage.append(img);
                } else if (product_file.file_type == '{{ FileType::IMAGE }}') {
                    const img = document.createElement('img');
                    img.src = BASE_URL + '/storage' + product_file.file_path;
                    img.classList.add('img-thumbnail', 'extraImagesPreview');
                    img.style.width = '200px';
                    previewDetailImage.append(img);
                } else if (product_file.file_type == '{{ FileType::VIDEO }}') {
                    const video = document.createElement('a');
                    video.href = BASE_URL + '/storage' + product_file.file_path;
                    video.textContent = product_file.file_name;
                    video.target = '_blank';
                    video.download = product_file.file_name;
                    document.querySelector('.productVideo').appendChild(video);
                } else if (product_file.file_type == '{{ FileType::PDF }}') {
                    const documentFile = document.createElement('a');
                    documentFile.href = BASE_URL + '/storage' + product_file.file_path;
                    documentFile.textContent = product_file.file_name;
                    documentFile.target = '_blank';
                    documentFile.download = product_file.file_name;
                    documentFile.style.display = 'block';
                    document.querySelector('.productDocuments').appendChild(documentFile);
                } else {

                }
            });

            if (product.is_featured == 1) {
                document.getElementById('isFeatured').checked = true;
            } else {
                document.getElementById('isFeatured').checked = false;
            }




            async function finalSubmit() {
                const spinner = document.getElementById('finalSubmitSpinner');
                console.log('final submit function called');
                if (!productImageForm.checkValidity() || !productTextsForm.checkValidity() || !productFilesForm
                    .checkValidity()) {
                    toastr.error(
                        'Few fields are missing, please go through all the tabs and fill the required fields.'
                    );
                    return;
                } else {
                    toastr.info('Submitting your product, please wait...');
                }
                const formData = new FormData(productTextsForm);

                for (const [key, value] of new FormData(productImageForm).entries()) {
                    formData.append(key, value);
                }

                for (const [key, value] of new FormData(productFilesForm).entries()) {
                    formData.append(key, value);
                }

                try {
                    spinner.classList.remove('d-none');
                    const response = await fetch(productTextsForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (response.ok) {
                        toastr.success('Product updated successfully!');
                        window.location.href = "/products";
                    } else {
                        for (const property in result.message) {
                            toastr.error(result.message[property][0]);
                        }
                        $("#finalSubmit").prop('disabled', false);
                        spinner.classList.add('d-none');
                    }
                } catch (error) {
                    console.error(error);
                    toastr.error(error.message);
                    // alert('An error occurred during submission.');
                }
            }

            // final submit
            $('#finalSubmit').on('click', function(e) {
                console.log('final submit click');
                if (!productFilesForm.checkValidity()) {
                    toastr.error('Please select a video and at least one document before proceeding.');
                    return;
                }

                $("#finalSubmit").prop('disabled', true); // Disable the button to prevent multiple clicks
                finalSubmit();
            });



            // files tab logic

            $('#filesTabPreviousBtn').on('click', function() {
                const prev = this.dataset.prev;

                $('#images-tab').removeClass('disabled');
                $('#files-tab').addClass('disabled');

                $('#productTab a[href="' + prev + '"]').tab('show');
            });

            const videoInput = document.getElementById('videoInstruction');
            const filesInput = document.getElementById('documentsInput');

            // Video preview
            videoInput.addEventListener('change', function() {

                let oversizedFile = false;
                const maxSizeMB = 50; // Maximum file size in MB


                for (const file of videoInput.files) {
                    if (file.size > maxSizeMB * 1024 * 1024) {
                        oversizedFile = true;
                        break;
                    }

                    if (!allowedVideoTypes.includes(file.type)) {
                        toastr.error(`"${file.name}" is not a valid video. Allowed type: MP4.`);
                        this.value = ''; // Clear the file input
                        return;
                    }
                }

                if (oversizedFile) {
                    toastr.error(`File size exceeds ${maxSizeMB} MB. Please upload a smaller file.`);
                    this.value = ''; // Clear the file input
                }
                // Remove old preview if any
                const oldPreview = document.querySelector('.video-preview');
                if (oldPreview) oldPreview.remove();

                const file = this.files[0];
                if (!file) return;

                const previewContainer = document.createElement('div');
                previewContainer.classList.add('video-preview', 'mt-3');

                const video = document.createElement('video');
                video.controls = true;
                video.width = 400;

                const source = document.createElement('source');
                source.src = URL.createObjectURL(file);
                source.type = file.type;

                video.appendChild(source);
                previewContainer.appendChild(video);

                document.querySelector('.productVideo').appendChild(previewContainer);
            });

            // Document filenames preview
            filesInput.addEventListener('change', function() {
                // Remove old file list if any
                const oldList = document.querySelector('.file-name-list');
                if (oldList) oldList.remove();

                const files = this.files;
                if (!files.length) {
                    toastr.error('No files selected.');
                    return;
                }
                if (files.length > 5) {
                    toastr.error('You can upload a maximum of 5 files.');
                    this.value = ''; // Clear the file input
                    return;
                }
                let isValid = true;
                const listContainer = document.createElement('ul');
                listContainer.classList.add('file-name-list', 'mt-2');

                for (let i = 0; i < files.length; i++) {

                    if (!allowedDocumentTypes.includes(files[i].type)) {
                        isValid = false
                    }

                    const li = document.createElement('li');
                    li.textContent = files[i].name;
                    listContainer.appendChild(li);
                }

                if (!isValid) {
                    toastr.error('Invalid file type. Only PDF files are allowed.');
                    this.value = ''; // Clear the file input
                    return;
                }
                document.querySelector('.productDocuments').appendChild(listContainer);
            });


            // first tab logic
            const productTextDataSubmit = document.getElementById('productTextDataSubmit');
            let allFilled = true;
            //Track if all required trix fields are filled
            let allowNextPage = false;
            productTextsForm.addEventListener('input',
                function(e) {
                    const trixEditors = document.querySelectorAll('trix-editor');
                    allFilled = true;
                    trixEditors.forEach(editor => {
                        const content = editor.editor.getDocument().toString();
                        if (!content.trim()) {
                            allFilled = false;
                        }
                    });

                    if (productTextsForm.checkValidity() && allFilled) {
                        productTextDataSubmit.disabled = false;
                    } else {
                        $('#images-tab').addClass('disabled');
                        productTextDataSubmit.disabled = true;
                    }
                })

            document.getElementById('productTextDataSubmit').addEventListener('click', function(e) {
                console.log('click success');
                const next = this.dataset.next;

                // validations
                const priorities = Array.from(document.querySelectorAll('select[name$="Priority"]'))
                    .map(select => select.value)
                    .filter(val => val !== "");

                const hasDuplicates = new Set(priorities).size !== priorities.length;
                const allNumericStrings = priorities.every(v => /^\d+$/.test(v));


                if ($('#productCategory').val() == '0' || $('#productSubCategory').val() == '0') {
                    if ($('#productCategory').val() == '0') {
                        toastr.error('Please select a category and subcategory.');
                    } else {
                        toastr.error('Please select a subcategory.');
                    }
                    return;

                }



                // feedbacks/toasts
                if (!allFilled) {
                    console.log('allfilled' + allFilled);
                    e.preventDefault();
                    toastr.error('Please fill all text fields marked with * before proceeding.');
                    return;
                }
                if (!productTextsForm.checkValidity()) {
                    console.log('invalid form');
                    toastr.error('Please fill all required fields before proceeding.');
                    return;
                }
                if (!allNumericStrings) {
                    e.preventDefault();
                    toastr.error('Please specify priorities for each section.');
                    return;
                }
                if (hasDuplicates) {
                    e.preventDefault();
                    toastr.error('Priority values must be unique for each section.');
                    return;
                }
                if (allFilled && !hasDuplicates && productTextsForm.checkValidity()) {
                    e.preventDefault();
                    $('#images-tab').removeClass('disabled');
                    // Show tab using Bootstrap's tab method
                    $('#productTab a[href="#images"]').tab('show');
                    $('#basic-tab').addClass('disabled');
                }

            });


            $('#productCategory').on('change', function() {
                console.log('category change');
                const categoryId = $(this).val();
                const subSelect = $('#productSubCategory');
                subSelect.empty().append('<option value="0">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: `/get-subcategories/${categoryId}`,
                        type: 'GET',
                        success: function(data) {
                            subSelect.empty().append(
                                '<option value="0">Select Subcategory</option>');
                            $.each(data, function(index, sub) {
                                subSelect.append(
                                    `<option value="${sub.id}">${sub.name}</option>`
                                );
                            });
                            productSubCategory.value = product.sub_category_id;

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


            productCategory.dispatchEvent(categoryChangeEvent);
            // image tab logic

            const productImageFormSubmit = document.getElementById('productImageSubmit');

            productImageFormSubmit.addEventListener('click', function(e) {
                console.log('image submit click');
                const next = this.dataset.next;
                const spinner = document.getElementById('imageSubmitSpinner');
                console.log(spinner);

                spinner.classList.remove('d-none');
                // validations
                if (!productImageForm.checkValidity()) {
                    e.preventDefault();
                    toastr.error(
                        'Please upload a main image and at least one detail image before proceeding.');
                    return;
                }

                // feedbacks/toasts
                if (productImageForm.checkValidity()) {
                    e.preventDefault();
                    const formData = new FormData(productImageForm);

                    $.ajax({
                        url: `/product/validate`,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.success == true) {
                                $('#images-tab').addClass('disabled');
                                spinner.classList.add('d-none');
                                productImageFormSubmit.innerHtml = "";
                                $('#files-tab').removeClass('disabled');

                                // Show tab using Bootstrap's tab method
                                $('#productTab a[href="#files"]').tab('show')
                                return;
                            } else {
                                for (const property in data.message) {
                                    toastr.error(data.message[property][0]);
                                }
                                spinner.classList.add('d-none');
                            }

                        },
                        error: function(data) {
                            toastr.error(data.message);
                        }
                    });
                }
            });

            document.getElementById('imagesTabPreviousBtn').addEventListener('click', function() {
                const prev = this.dataset.prev;
                console.log(prev);

                $('#basic-tab').removeClass('disabled');
                $('#images-tab').addClass('disabled');

                $('#productTab a[href="' + prev + '"]').tab('show');
            });

            document.getElementById('productImage').addEventListener('change', function() {
                // Remove existing preview if any
                const oldPreview = document.querySelector('.productImagePreview');
                if (oldPreview) oldPreview.remove();

                const file = this.files[0];
                if (!file) return;

                const maxSizeMB = 3;
                const maxSizeBytes = maxSizeMB * 1024 * 1024;

                if (file.size > maxSizeBytes) {
                    $('#warning').removeAttr('hidden');
                    $('#warning').text(`File size exceeds ${maxSizeMB} MB. Please upload a smaller file.`);
                    this.value = ''; // Clear the file input
                    return;
                }

                if (!allowedImageTypes.includes(file.type)) {
                    toastr.error(`"${file.name}" is not a valid image. Allowed types: JPG, JPEG, PNG.`);
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.createElement('div');
                    previewContainer.classList.add('productImagePreview', 'image-preview');

                    const wrapper = document.createElement('div');
                    wrapper.classList.add('image-wrapper', 'm-1', 'd-inline');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '200px';

                    wrapper.appendChild(img);
                    previewContainer.appendChild(wrapper);
                    document.querySelector('.productMainImage').appendChild(previewContainer);
                };

                reader.readAsDataURL(file);

                productImageFormSubmit.disabled = !productImageForm.checkValidity();

            });

            document.getElementById('productDetailImages').addEventListener('change', function() {

                console.log('detail image change');
                const imagesLimit = 5; // Maximum number of detail images allowed
                if (this.files.length > imagesLimit) {
                    toastr.error('You can upload maximum ' + imagesLimit + ' detail images.');
                    this.value = ''; // Clear the file input
                    return;
                }

                // Remove any previous previews
                const oldPreview = document.querySelector('.extraImagesPreview');
                if (oldPreview) oldPreview.remove();

                const previewContainer = document.createElement('div');
                previewContainer.classList.add('extraImagesPreview', 'image-preview', 'd-flex',
                    'flex-wrap');
                document.querySelector('.productDetailImages').appendChild(previewContainer);

                const maxSizeMB = 3;
                const maxSizeBytes = maxSizeMB * 1024 * 1024;

                const files = this.files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.size > maxSizeBytes) {
                        alert(`File "${file.name}" exceeds ${maxSizeMB} MB limit and will be skipped.`);
                        continue;
                    }
                    if (!allowedImageTypes.includes(file.type)) {
                        toastr.error(`"${file.name}" is not a valid image. Allowed types: JPG, JPEG, PNG.`);
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('image-wrapper', 'm-1', 'd-inline');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-thumbnail');
                        img.style.width = '200px';

                        wrapper.appendChild(img);
                        previewContainer.appendChild(wrapper);
                    };

                    reader.readAsDataURL(file);

                }
                productImageFormSubmit.disabled = !productImageForm.checkValidity();
            });












        });
        // Optional: activate tab via JS
        $('#productTab a').on('click', function(e) {
            console.log('test');
            e.preventDefault();
            // $(this).tab('show');
        });
    </script>
@endpush

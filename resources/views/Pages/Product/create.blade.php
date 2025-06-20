@extends('layouts.app')

@php
    use App\Models\Product;
    use App\Enums\Status;
    use App\Constants\ProductConstants as CONSTANTS;

    //* or any other dependencies

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
            <form data-validate id="createProductForm">
                <div class="form-group">
                    <label for="productName">Product Name*</label>
                    <input type="email" class="form-control" id="productName" name="name">
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Description*</label>
                    @trix(\App\Models\CmsPage::class, CONSTANTS::PRODUCT_DESCRIPTION, ['hideButtonIcons' => ['attach']])
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">More Information*</label>
                    @trix(\App\Models\CmsPage::class, CONSTANTS::PRODUCT_INFORMATION, ['hideButtonIcons' => ['attach']])
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Characteristics*</label>
                    @trix(\App\Models\CmsPage::class, CONSTANTS::PRODUCT_CHARACTERISTICS, ['hideButtonIcons' => ['attach']])
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Warranty List*</label>
                    @trix(\App\Models\CmsPage::class, CONSTANTS::PRODUCT_WARRANTY_LIST, ['hideButtonIcons' => ['attach']])
                </div>
                <div class="form-group">
                    <label for="language" class="form-label">Service List*</label>
                    @trix(\App\Models\CmsPage::class, CONSTANTS::PRODUCT_SERVICE_LIST, ['hideButtonIcons' => ['attach']])
                </div>

                <textarea id="editor"><?php echo htmlspecialchars('<h1>Example</h1><p>Hello world</p>'); ?></textarea>

                <script type="module">
                    import {
                        ClassicEditor,
                        CKBox,
                        Essentials,
                        Bold,
                        Italic,
                        Font,
                        Paragraph
                    } from 'ckeditor5';

                    ClassicEditor
                        .create(document.querySelector('#editor'), {
                            plugins: [CKBox, Essentials, Bold, Italic, Font, Paragraph],
                            ckbox: {
                                tokenUrl: 'https://your.token.url',
                                theme: 'lark'
                            },
                            toolbar: [
                                'ckbox', '|', 'undo', 'redo', '|', 'bold', 'italic', '|',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                            ],
                        })
                        .catch(error => {
                            console.error(error);
                        });
                </script>
            </form>
        </div>
    </div>
@stop

@push('js')
    {{-- @trixassets --}}
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/45.1.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/45.1.0/"
            }
        }
    </script>
@endpush

@push('css')
    <style>
        trix-editor {
            min-height: 10em !important;
            max-height: 25em;
            overflow-y: auto;
        }
    </style>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.1.0/ckeditor5.css" />

@endpush

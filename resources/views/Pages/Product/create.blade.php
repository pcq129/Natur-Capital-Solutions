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
                <div class="main-container">
                    <div class="editor-container editor-container_classic-editor editor-container_include-block-toolbar"
                        id="editor-container">
                        <div class="editor-container__editor">
                            <div id="editor"></div>
                        </div>
                    </div>
                </div>
                <script type="module" src="./main.js"></script>
                {{-- <div class="form-group">
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
                </div> --}}
            </form>
        </div>
    </div>
@stop

@push('js')
    /**
    * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
    *
    https://ckeditor.com/ckeditor-5/builder/#installation/NoNgNARATAdCMEYKRADgKwGZWYRzUIIm6ADJiApqaSAJymEKkLpsWnIQCmAdsqTDAEYQSJFiAupDxoECAGYRJQA=
    */

    import {
    ClassicEditor,
    Autoformat,
    Autosave,
    BlockQuote,
    BlockToolbar,
    Bold,
    Essentials,
    Heading,
    Indent,
    IndentBlock,
    Italic,
    Link,
    Paragraph,
    TextTransformation,
    Underline
    } from 'ckeditor5';

    import 'ckeditor5/ckeditor5.css';

    import './style.css';

    /**
    * Create a free account with a trial: https://portal.ckeditor.com/checkout?plan=free
    */
    const LICENSE_KEY = 'GPL'; // or <YOUR_LICENSE_KEY>.

        const editorConfig = {
        toolbar: {
        items: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', 'underline', '|', 'link', 'blockQuote', '|',
        'outdent', 'indent'],
        shouldNotGroupWhenFull: false
        },
        plugins: [
        Autoformat,
        Autosave,
        BlockQuote,
        BlockToolbar,
        Bold,
        Essentials,
        Heading,
        Indent,
        IndentBlock,
        Italic,
        Link,
        Paragraph,
        TextTransformation,
        Underline
        ],
        blockToolbar: ['bold', 'italic', '|', 'link', '|', 'outdent', 'indent'],
        heading: {
        options: [
        {
        model: 'paragraph',
        title: 'Paragraph',
        class: 'ck-heading_paragraph'
        },
        {
        model: 'heading1',
        view: 'h1',
        title: 'Heading 1',
        class: 'ck-heading_heading1'
        },
        {
        model: 'heading2',
        view: 'h2',
        title: 'Heading 2',
        class: 'ck-heading_heading2'
        },
        {
        model: 'heading3',
        view: 'h3',
        title: 'Heading 3',
        class: 'ck-heading_heading3'
        },
        {
        model: 'heading4',
        view: 'h4',
        title: 'Heading 4',
        class: 'ck-heading_heading4'
        },
        {
        model: 'heading5',
        view: 'h5',
        title: 'Heading 5',
        class: 'ck-heading_heading5'
        },
        {
        model: 'heading6',
        view: 'h6',
        title: 'Heading 6',
        class: 'ck-heading_heading6'
        }
        ]
        },
        initialData:
        '<h2>Congratulations on setting up CKEditor 5! 🎉</h2>\n<p>\n\tYou\'ve successfully created a CKEditor 5 project.
            This powerful text editor\n\twill enhance your application, enabling rich text editing capabilities that\n\tare
            customizable and easy to use.\n</p>\n<h3>What\'s next?</h3>\n<ol>\n\t<li>\n\t\t<strong>Integrate into your
                    app</strong>: time to bring the editing into\n\t\tyour application. Take the code you created and add to
                your application.\n\t</li>\n\t<li>\n\t\t<strong>Explore features:</strong> Experiment with different plugins
                and\n\t\ttoolbar options to discover what works best for your needs.\n\t</li>\n\t<li>\n\t\t<strong>Customize
                    your editor:</strong> Tailor the editor\'s\n\t\tconfiguration to match your application\'s style and
                requirements. Or\n\t\teven write your plugin!\n\t</li>\n</ol>\n<p>\n\tKeep experimenting, and don\'t
            hesitate to push the boundaries of what you\n\tcan achieve with CKEditor 5. Your feedback is invaluable to us as
            we strive\n\tto improve and evolve. Happy editing!\n</p>\n<h3>Helpful resources</h3>\n<ul>\n\t<li>📝 <a
                    href="https://portal.ckeditor.com/checkout?plan=free">Trial sign up</a>,</li>\n\t<li>📕 <a
                    href="https://ckeditor.com/docs/ckeditor5/latest/installation/index.html">Documentation</a>,</li>\n\t
            <li>⭐️ <a href="https://github.com/ckeditor/ckeditor5">GitHub</a> (star us if you can!),</li>\n\t<li>🏠 <a
                    href="https://ckeditor.com">CKEditor Homepage</a>,</li>\n\t<li>🧑‍💻 <a
                    href="https://ckeditor.com/ckeditor-5/demo/">CKEditor 5 Demos</a>,</li>\n</ul>\n<h3>Need help?</h3>\n<p>
            \n\tSee this text, but the editor is not starting up? Check the browser\'s\n\tconsole for clues and guidance. It
            may be related to an incorrect license\n\tkey if you use premium features or another feature-related
            requirement. If\n\tyou cannot make it work, file a GitHub issue, and we will help as soon as\n\tpossible!\n</p>
        \n',
        licenseKey: LICENSE_KEY,
        link: {
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://',
        decorators: {
        toggleDownloadable: {
        mode: 'manual',
        label: 'Downloadable',
        attributes: {
        download: 'file'
        }
        }
        }
        },
        placeholder: 'Type or paste your content here!'
        };

        ClassicEditor.create(document.querySelector('#editor'), editorConfig);

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.ck-editor').forEach(function(textarea) {
                    ClassicEditor
                        .create(textarea)
                        .catch(error => {
                            console.error(error);
                        });
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

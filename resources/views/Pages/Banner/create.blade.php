@extends('layouts.app')



@section('content_header')
    <div class="row mb-2 justify-content-between">
        <div class="col-sm-6">
            <h1>Create New Banner</h1>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <form class="card ms-4 col-8 p-3" action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-12">
                    <label for="bannerName" class="form-label">Name</label>
                    <input name="bannerName" type="text" id="bannerName" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="overlayHeading" class="form-label">Overlay Heading</label>
                    <input name="overlayHeading" type="text" id="overlayHeading" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="overlaySubText" class="form-label">Overlay Sub-Text</label>
                    <textarea name="overlaySubtext" type="text" id="overlaySubText" class="form-control" rows="4"></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="bannerLink" class="form-label">Banner Link</label>
                    <input name="bannerLink" type="text" id="bannerLink" class="form-control">
                </div>
            </div>

            <div class="row mb-3 g-3">
                <div class="col-6">
                    <label class="form-label">Image</label>
                    <br>
                    <label for="file-upload" class="bannerImageUpload fw-bold mb-2">Upload</label>
                    <input id="file-upload" name="bannerImage" type="file" accept=".jpg, .jpeg, .png" class="form-control">
                </div>

                <div class="col-6">
                    <label class="form-label">Priority/Order</label>
                    <br>
                    <input name="priority" type="number" class="form-control col-3" min="0" max=>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="bannerButtonText" class="form-label">Buttons</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input name="bannerButtonOneText" type="text" id="bannerButtonText" class="form-control" placeholder="Button Text">
                        </div>
                        <div class="col-md-6">
                            <input name="bannerButtonOneAction" type="text" id="bannerButtonAction" class="form-control"
                                placeholder="Button Action (Redirection Link)">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <input type="text" name="bannerButtonTwoText" id="bannerButtonText" class="form-control" placeholder="Button 2 Text">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="bannerButtonTwoAction" id="bannerButtonAction" class="form-control"
                                placeholder="Button 2 Action (Redirection Link)">
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-12">
                    <label for="bannerLinkText" class="form-label">Links</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="bannerLinkOneText" id="bannerLinkText" class="form-control" placeholder="Link Text">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="bannerLinkOneAction" id="bannerLinkAction" class="form-control"
                                placeholder="Link Action (Redirection Link)">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <input type="text" name="bannerLinkTwoText" id="bannerLinkText" class="form-control" placeholder="Link 2 Text">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="bannerLinkTwoAction" id="bannerLinkAction" class="form-control"
                                placeholder="Link 2 Action (Redirection Link)">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
@stop

@extends('admin.layouts.apps')
@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Product</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('products.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <form action="" class="productForm" id="productForm" name="productForm" action="" method="POST">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="Title" value="{{$product->title}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">slug</label>
                                        <input type="text" name="slug" id="editSlug" class="form-control"
                                            placeholder="slug" readonly>
                                        <p class="error"></p>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="summernote" cols="30" rows="10"
                                            class="summernote" placeholder="Description">
                                          {{ $product->description }}
                                        </textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Media</h2>
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">
                                    <br>Drop files here or click to upload.<br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product-gallery"></div>
                    @if ($productImages->isNotEmpty())
                        @foreach ($productImages as $image)
                            <input type="hidden" id="image-row-{{$image->id}}" name="image_array[]" value="{{$image->id}} ">
                            <div class="col-md-3">
                                <div class="card" style="">
                                <img src="{{ asset('/uploads/product/small/'. $image->image ) }}" class="card-img-top" alt="">

                                    <div class="card-body"><a href="javascript:void(0)" onclick="deleteImage({{$image->id}})"
                                            class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>


                        @endforeach

                    @endif

                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Pricing</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control"
                                            placeholder="Price" value="{{$product->price}}">
                                        <p class="error"></p>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Compare at Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control"
                                            placeholder="Compare Price" value="{{$product->compare_price}}">
                                        <p class="text-muted mt-3">
                                            To show a reduced price, move the product’s original price into Compare at
                                            price. Enter a lower value into Price.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventory</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku"
                                            value="{{$product->sku}}">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control"
                                            placeholder="Barcode" value="{{$product->barcode}}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty"
                                                value="Yes" name="track_qty" checked {{ ($product->track_qty == 'Yes') ? 'checked' : ''}}>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>




                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control"
                                            placeholder="Qty" value="{{$product->qty}}">
                                        <p class="error"></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{  ($product->status == '1') ? 'selected' : ''}} value="1">Active</option>
                                    <option {{  ($product->status == '0') ? 'selected' : ''}} value="0">Block</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4  mb-3">Product category</h2>
                            <div class="mb-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">

                                    <option value="">select a category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                            {

                                            <option {{ ($product->category_id == $category->id) ? 'selected' : '' }}
                                                value="{{ $category->id }}">{{ $category->name }}</option>

                                            }
                                        @endforeach
                                    @endif
                                    </option>
                                </select>
                                <p class="error"></p>

                            </div>
                            <div class="mb-3">
                                <label for="category">Sub category</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">select a sub category</option>
                                    @if ($subCategories->isNotEmpty())
                                        @foreach ($subCategories as $subCategory)
                                            {

                                            <option {{ ($product->sub_category_id == $subCategory->id) ? 'selected' : '' }}
                                                value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>

                                            }
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Product brand</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">select product brand</option>
                                    @if ($brands->isNotEmpty())
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ ($product->brand_id == $brand->id) ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="error"></p>

                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Featured product</h2>
                            <div class="mb-3">
                                <p>Is Featured: {{ $product->is_featured }}</p>
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="NO" {{ $product->is_featured === 'NO' ? 'selected' : '' }}>No</option>
                                    <option value="YES" {{ $product->is_featured === 'YES' ? 'selected' : '' }}>Yes
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button class="btn btn-primary" type="submit">Create</button>
                <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </div>
    </form>

    <!-- /.card -->
</section>
@endsection
@section('customJs')
<script>


    //    product form submission
    $(document).ready(function () {

        $("#productForm").submit(function (event) {
            event.preventDefault(); // Prevent form submission
            var element = $("#productForm")
            $("button[type=submit]").prop('disabled', true); // Disable submit button

            $.ajax({
                url: "{{ route('products.store') }}", // Route for form submission
                type: 'POST',
                data: element.serializeArray(), // Serialize form data
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $("button[type=submit]").prop('disabled',
                        false); // Re-enable submit button

                    if (response['status'] == true) {
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type=text], select").removeClass('is-valid');
                        window.location.href = "{{ route('products.index') }}";


                    } else {
                        var errors = response['errors'];
                        $(".error").removeClass('invalid-feedback').html('');
                        $("input[type=text], select").removeClass('is-valid');
                        $.each(errors, function (key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(value);



                        });


                    }


                },

                error: function (jqXHR, exception) {
                    console.log("Something went wrong: ", jqXHR.responseText);
                    $("button[type=submit]").prop('disabled', false); // Re-enable on error
                }
            });

        });
        $("#category").change(function () {
            var category_id = $(this).val();

            $.ajax({
                url: "{{ route('products.update', $product->id) }}",

                data: {
                    category_id: category_id
                },

                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"], function (key, item) {
                        $("#sub_category").append(
                            `<option value = '${item.id}'> ${item.name} </option>`
                        )

                    })
                }
            });


        });
        Dropzone.autoDiscover = false;
        var dropzone = new Dropzone("#image", {
            url: "{{ route('products-images.update') }}",
            maxFiles: 10,
            paramName: 'image',
            params:{'product_id':'{{$product->id}}'},
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (file, response) {
                var html =`
<input type="hidden" id="image-row-${response.image_id}" name="image_array[]" value="${response.image_id}">
<div class="col-md-3">
    <div class="card">
        <img src="${response.imagePath}" class="card-img-top" alt="">
        <div class="card-body">
            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
        </div>
    </div>
</div>`;

                $("#product-gallery").append(html);

            },
            complete: function (file) {
                this.removeFile(file);

            },

        });


    });
    function deleteImage(imageId) {
        // Remove the image element from the gallery
        document.getElementById(`image-row-${imageId}`).parentNode.remove();

        // Optionally, you can send a request to delete the image on the server if needed
        // Example:
        /*
        $.ajax({
            url: '/path-to-delete-image/' + imageId,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Image deleted successfully');
            },
            error: function(error) {
                console.error('Error deleting image');
            }
        });
        */
    }


</script>
<script>
    // Slug generation based on title input
    $("#title").change(function () {
        $("button[type=submit]").prop('disabled', true);
        let element = $(this);

        $.ajax({
            url: "{{ route('getSlug') }}",
            type: 'GET',
            data: {
                title: element.val()
            },
            dataType: 'json',
            success: function (response) {
                $("button[type=submit]").prop('disabled', false);
                if (response.status === true) {
                    $("#editSlug").val(response.slug);
                }
            }
        });
    });
</script>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

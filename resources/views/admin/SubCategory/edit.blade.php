@extends('admin.layouts.apps')

@section('content')

    <!-- Site wrapper -->
    <section class="content-header">
        <div class="container-fluid my-2">

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Sub Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" name="subCategoryForm" id="subCategoryForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category" id="category" class="form-control" >


                                        <option value="">select a category</option>



                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option   {{ ($subCategory->category_id==$category->id)? 'selected':'' }}
                                                      value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                        <option value="">Mobile</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $subCategory->name }}">
                                        <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" value="{{ $subCategory->slug }}">
                                        <p></p>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option {{ ($subCategory->status == 1)? 'selected':'' }} value="1">Active</option>
                                            <option {{ ($subCategory->status == 0)? 'selected':'' }} value="0">Block</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                    </div>
            </form>
        </div>

        <!-- /.card -->
    </section>









@section('customJs')
    <script>
        // subcategory form submission
        $(document).ready(function() {
        $("#subCategoryForm").submit(function(event) {
            event.preventDefault(); // Prevent form submission
            var element = $("#subCategoryForm"); // Form element
            $("button[type=submit]").prop('disabled', true);


            $.ajax({
                url: "{{ route('sub-categories.update', $subCategory->id) }}", // Route for form submission
                type: 'PUT',
                data: element.serializeArray(), // Serialize form data
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable submit button

                    // Check if the response status is true
                    if (response.status === true) {
                        window.location.href = "{{ route('sub-categories.index') }}"; // Redirect to index
                    }

                    // Handle validation errors if present
                    if (response.errors) {
                        var errors = response.errors;

                        if (errors.name) {
                            $('#name').addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors.name.join(', '));
                        } else {
                            $('#name').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.slug) {
                            $('#slug').addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors.slug.join(', '));
                        } else {
                            $('#slug').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.category) {
                            $('#category').addClass('is-invalid')
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors.category.join(', '));
                        } else {
                            $('#category').removeClass('is-invalid')
                                .siblings('p').removeClass('invalid-feedback')
                                .html('');
                        }
                    } else {
                        // Clear previous validation errors
                        $('#name, #slug,#category,').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');
                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong: ", jqXHR.responseText);
                    $("button[type=submit]").prop('disabled', false); // Re-enable on error
                }
            });
        });
    });
        // Slug generation based on name input
        $("#name").change(function() {
            $("button[type=submit]").prop('disabled', true);
            let element = $(this);

            $.ajax({
                url: "{{ route('getSlug') }}",
                type: 'GET',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response.status === true) {
                        $("#slug").val(response.slug);
                    }
                }
            });
        });
    </script>
@endsection
<!-- Include jQuery (make sure it's loaded before your script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>












@endsection

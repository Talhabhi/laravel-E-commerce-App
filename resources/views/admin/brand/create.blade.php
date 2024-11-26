@extends('admin.layouts.apps')
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Brand</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="brands.html" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" class="" id="createBrandForm" name="createBrandForm" method="">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                        <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control"
                                        placeholder="Slug">
                                        <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" >Active</option>
                                        <option value="0"  >Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button class="btn btn-primary" type="submit">Create</button>
                    <a href="brands.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>

        </div>
        <!-- /.card -->
    </section>
@endsection
@section('customJs')
    <script>
        // subcategory form submission
        $(document).ready(function() {
        $("#createBrandForm").submit(function(event) {
            event.preventDefault(); // Prevent form submission
            var element = $("#createBrandForm"); // Form element
            $("button[type=submit]").prop('disabled', true); // Disable submit button

            $.ajax({
                url: "{{ route('brands.store') }}", // Route for form submission
                type: 'POST',
                data: element.serializeArray(), // Serialize form data
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable submit button

                    // Check if the response status is true
                    if (response.status === true) {
                        window.location.href = "{{ route('brands.index') }}"; // Redirect to index
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


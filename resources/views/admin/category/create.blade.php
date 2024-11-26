@extends('admin.layouts.apps')

@section('content')
    <section class="content-header">
        <!-- Your existing content header code -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="" method="POST" id="categoryForm" name="categoryForm"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="col-sm-6 text-start">
                        <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ old('name') }}">
                                        <p></p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug"  id="slug" class="form-control"
                                        placeholder="Slug" value="{{ old('slug') }}">
                                        <p></p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create</button>
                    <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>



    <!-- Include jQuery (make sure it's loaded before your script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $("#categoryForm").submit(function(event) {
            event.preventDefault(); // Prevent form submission
            var element = $(this); // Form element
            $("button[type=submit]").prop('disabled', true); // Disable submit button

            $.ajax({
                url: "{{ route('categories.store') }}", // Route for form submission
                type: 'POST',
                data: element.serializeArray(), // Serialize form data
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false); // Re-enable submit button

                    // Check if the response status is true
                    if (response.status === true) {
                        window.location.href = "{{ route('categories.index') }}"; // Redirect to index
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
                    } else {
                        // Clear previous validation errors
                        $('#name, #slug').removeClass('is-invalid')
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


        // autofill slug when name is inserting

        // Dropzone initialization


            Dropzone.autoDiscover = false;
            var dropzone = new Dropzone("#image", {
                url: "{{ route('dropzone.create') }}",
                maxFiles: 1,
                paramName: 'image',
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                init: function() {
                    console.log("Dropzone initialized");

                    // Handle success response after upload
                    this.on('success', function(file, response) {
                        $("#image_id").val(response.image_id);
                    });
                }
            });


    });
</script>










@endsection


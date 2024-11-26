@extends('admin.layouts.apps')

@section('content')
    <section class="content-header">
        <!-- Your existing content header code -->
    </section>

    <section class="content">
        <h3>Edit Category</h3>
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
                                        placeholder="Name" value="{{ $category->name }}">
                                        <p></p>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug"  id="slug" class="form-control"
                                        placeholder="Slug" value="{{ $category->slug }}">
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
                            @if (!empty($category->image))
                            <div>
                                <img
                                 width="200" src="{{ asset('/uploads/category/thumb/' .
                                  $category->image ) }}" alt="">

                            </div>


                            @endif


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{$category->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>
                    <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>


    @section('customJS')
    <script>
        $("#categoryForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled' , true);




            $.ajax({
                url: '{{ route("categories.update", $category->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled' , false);

                    // Check if there are errors in the response
                    if (response['status']==true) {
                        window.location.href= "{{route('categories.index')  }}";

                    }
                    if (response.errors) {


                        var errors = response.errors;
                        if (errors.name) {
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors.name.join(', '));
                        } else {
                            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                                .html('');
                        }
                        if (errors.slug) {
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors.slug.join(', '));
                        } else {
                            $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                                .html('');
                        }
                    } else {
                        // Handle the case where there are no errors
                        $('#name, #slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                            .html('');

                    }
                },
                error: function(jqXHR, exception) {
                    console.log("Something went wrong: ", jqXHR.responseText);
                    // Optionally, display error messages to the user
                }
            });
        });
    </script>


    @endsection


















@endsection


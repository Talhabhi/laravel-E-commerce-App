<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\JsonResponse;



class categoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');





        }
        $categories = $categories->paginate(10);
        // $data['categories']=$categories;
        return view('admin.category.list', compact('categories'));
    }


    public function create()
    {
        return view('admin.category.create');
    }



    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required|in:0,1',

        ]);
        if ($validator->passes()) {
            $category = new category();

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            // delete old image veriable defined


            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $tempImageArray = explode('.', $tempImage->name);
                $ext = last($tempImageArray);

                $newImageName = $category->id . '.' . $ext;

                $sPath = public_path() . "/temp/" . $tempImage->name;
                $dPath = public_path() . "/uploads/category/" . $newImageName;
                file::copy($sPath, $dPath);
                $category->image = $newImageName;
                $category->save();


                // now we resize our image through image intervention 3

                $imgManager = new ImageManager(new Driver());
                $dPath = public_path() . "/uploads/category/thumb/" . $newImageName;
                $thumbImage = $imgManager->read($sPath);
                $thumbImage->resize(450, 600);
                $thumbImage->save($dPath);


                // delete old images code






            }


            session()->flash('success', 'category is created successfully');


            return response()->json([
                'status' => true,
                'message' => 'category is created is successfully',

            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }



    }




    // // If validation passes











    public function edit($categoryId, Request $request)
    {
        $category = category::find($categoryId);
        if (empty($category)) {



            return redirect()->route('categories.index');

        }

        return view('admin.category.edit', compact('category'));

    }


    public function update($categoryId, Request $request)
    {
        $category = category::find($categoryId);
        if (empty($category)) {


// session()->flash('Error', 'category not found error');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'category not found'



            ]);

        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
            'status' => 'required|in:0,1',

        ]);
        if ($validator->passes()) {


            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $tempImageArray = explode('.', $tempImage->name);
                $ext = last($tempImageArray);
                $newImageName = $category->id . '-' . time() . '.' . $ext;

                // $newImageName = $category->id . '-' . time() . '.' . $ext;

                $sPath = public_path() . "/temp/" . $tempImage->name;
                $dPath = public_path() . "/uploads/category/" . $newImageName;
                file::copy($sPath, $dPath);


                $oldImage = $category->image;


                // now we resize our image through image intervention 3

                $imgManager = new ImageManager(new Driver());
                $dPath = public_path() . "/uploads/category/thumb/" . $newImageName;
                $thumbImage = $imgManager->read($sPath);
                $thumbImage->resize(450, 600);
                $thumbImage->save($dPath);
                $category->image = $newImageName;
                $category->save();



                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/category/' . $oldImage);





            }


            session()->flash('success', 'category is updated successfully');


            return response()->json([
                'status' => true,
                'message' => 'category is updated successfully',

            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }

    }

    public function destroy($categoryId, Request $request)
    {
        $category = category::find($categoryId);
        if (empty($category)) {
            session()->flash('Error', 'category not found error');
            return response()->json([
                'status'=>true,
                'message'=> 'category not found error',
            ]);
        }
        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);
        $category->delete();
        session()->flash('success', 'category is deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'category is deleted successfully'
        ]);

    }
    public function list()
    {


    }
}

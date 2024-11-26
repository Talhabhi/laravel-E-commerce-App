<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');
        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%' . $request->get('keyword') . '%');





        }
        $subCategories = $subCategories->paginate(10);
        // $data['categories']=$categories;
        return view('admin.SubCategory.list', compact('subCategories'));
    }
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        return view('admin.SubCategory.create', $data);


    }
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            // 'category_id'=> 'required',
            'category' => 'required',

            'status' => 'required',



        ]);
        if ($validator->passes()) {
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();
            session()->flash('success', 'sub category created successfully.');


            return response([
                'status' => true,
                'message' => 'sub category created successfully.',
            ]);


        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }
    public function edit($id, Request $request)
    {
        $subCategory = SubCategory::find($id);


        if (empty($subCategory)) {
            session()->flash('error', 'record not found');
            return redirect()->route('sub-categories.index');

        }


        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;
        return view('admin.SubCategory.edit', $data);




    }
    public function update($id, Request $request)
    {
        $subCategory = SubCategory::find($id);


        if (empty($subCategory)) {
            session()->flash('error', 'record not found');
            // return redirect()->route('sub-categories.index');
            return response([
                'status' => false,
                'notFound' => true,
                'message' => 'category not found'
            ]);


        }
        $validator = validator::make($request->all(), [
            'name' => 'required',
            // 'slug'=> 'required|unique:sub_category',
            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',

            // 'category_id'=> 'required',
            'category' => 'required',

            'status' => 'required',



        ]);
        if ($validator->passes()) {
            // $subCategory=new   SubCategory ();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();
            session()->flash('success', 'sub category created successfully.');


            return response([
                'status' => true,
                'message' => 'sub category created successfully.',
            ]);


        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }


    }
    public function destroy($id, Request $request)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            session()->flash('error', 'category not found error');
            return response([
                'status' => false,
                'notFound' => true

            ]);


        }
        $subCategory->delete();
        session()->flash('success', 'sub category deleted successfully');
        return response([
            'status'=> true,
            'message'=> 'sub category delete successfully'
        ]);


    }

}

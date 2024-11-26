<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest('id');
        if ($request->get('keyword')) {
            $brands = $brands->where('name', 'like', '%' . $request->keyword . '%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brand.list', compact('brands'));

    }
    public function create(Request $request)
    {



        return view('admin.brand.create');


    }
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',



        ]);
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            session()->flash('success', 'brand created successfully');
            return response()->json([
                'status' => true,
                'message' => 'brand created successfully'

            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

        }




    }
    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            session()->flash('error', 'record not found error');
            return redirect()->route('brands.index');



        }
        $data['brand'] = $brand;
        return view('admin.brand.edit', $data);


    }
    public function update($id, Request $request)
    {
        $brand = Brand::find($id);


        if (empty($brand)) {
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
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',

            // 'category_id'=> 'required',


            'status' => 'required',



        ]);
        if ($validator->passes()) {
            // $subCategory=new   SubCategory ();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;

            $brand->save();
            session()->flash('success', 'Brand updated  successfully.');


            return response([
                'status' => true,
                'message' => 'Brand updated successfully.',
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
        $brands = Brand::find($id);

        if (empty($brands)) {
            session()->flash('error', 'Brand not found error');
            return response([
                'status' => false,
                'notFound' => true

            ]);

        }
        $brands->delete();
        session()->flash('success', 'Brand deleted successfully');
        return response([
            'status'=> true,
            'message'=>'Brand deleted successfully'
        ]);

    }
}

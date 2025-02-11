<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if ($request->get('keyword') !='') {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }
        $products = $products->paginate();
        // dd($products);

        $data['products'] = $products;

        return view('admin.products.list', $data);


    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.products.create', $data);

    }
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:YES,NO',


        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();
            // save gallery product images
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);




                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'Null';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    // save image on a path
                    $imgManager = new ImageManager(new Driver());

                    $sPath = public_path() . '/temp/' . $tempImageInfo->name;
                    $dPath = public_path() . '/uploads/product/large/' . $imageName;
                    $image = $imgManager->read($sPath);
                    $image->resize(300, 250);
                    $image->save($dPath);
                    $dPath = public_path() . '/uploads/product/small/' . $imageName;
                    $image = $imgManager->read($sPath);
                    $image->resize(300, 300);
                    $image->save($dPath);




                }
            }

            session()->flash('success', 'product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'product added successfully',
            ]);




        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }



    }
    public function edit($id , Request $request){
        $product = Product::find($id);
        if (empty($product)) {
            return redirect( )->route('products.index')->with('error', 'products not found');
        };
        $productImages= ProductImage::where('product_id',$product->id)->get();



        $subCategories=SubCategory::where('category_id',$product->category_id)->get();

        $data= [];

        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['product']= $product;
        $data['subCategories']= $subCategories;
        $data['productImages']= $productImages;

        // dd($categories);
        $data['brands'] = $brands;


        return view('admin.products.edit', $data);

    }
    public function update ($id , Request $request){
        $product =Product::find($id);
        $rules = [
            'title' => 'required',
            // 'slug' => 'required|unique:products,slug',
            'slug' => 'required|unique:Products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'. $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:YES,NO',


        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();
            // save gallery product images


            session()->flash('success', 'product updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'product updated successfully',
            ]);




        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }



    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        // Check if the request has the 'id' parameter
        if (!$request->has('id')) {
            return response()->json(['status' => false, 'message' => 'Product ID is required'], 400);
        }

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourceImage = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->id;

        // Generate the image name before saving
        $imageName = $request->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;

        $productImage->save(); // Save after setting the image name

        $imgManager = new ImageManager(new Driver());

        // Save large image
        $dPath = public_path() . '/uploads/product/large/' . $imageName;
        $image = $imgManager->read($sourceImage);
        $image->resize(300, 250);
        $image->save($dPath);

        // Save small image
        $dPath = public_path() . '/uploads/product/small/' . $imageName;
        $image = $imgManager->read($sourceImage);
        $image->resize(300, 300);
        $image->save($dPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('/uploads/product/small/' . $productImage->image),
            'message' => 'Image saved successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers\admin;




use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Http\Request;









class TempImagesController extends Controller
{
    public function create(Request $request)
    {

       $image = $request->image;
       if (!empty($image)) {
         $ext = $image->getClientOriginalExtension();
         $newName= time(). '.'.$ext;
         $tempImage=new TempImage();
         $tempImage->name =$newName;
         $tempImage->save();
         $image-> move(public_path().'/temp/', $newName);
        //  generate thumbnail
        $imgManager = new ImageManager(new Driver());
        $sourceImage= public_path().'/temp/'. $newName;
        $destImage= public_path().'/temp/thumb/'. $newName;
        $image= $imgManager->read($sourceImage);
        $image->cover(300,270);
        $image->save($destImage);



        // $image=Image::make($sourceImage);
        // $image->fit(300, 300);
        // $image->save($destImage);


         return response()->json([
            'status'=> true,
            'image_id'=>$tempImage->id,
            'imagePath'=>asset('/temp/thumb/'.$newName),
            'message'=>'image uploaded successfully talha bhi'



         ]);

        // $tempImage->

       }

    }

}

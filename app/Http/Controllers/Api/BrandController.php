<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();

        return response()->json([
            "status" => "ok",
            "totalResults" => count($brands), 
            "brands" => BrandResource::collection($brands)
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $messages = [
            'name.required' => '* Please enter Brand Name.',
            'name.min' => 'Brand Name should be 3 letters and more.',
            'photo.required' => '* Please choose Brand Photo.',
            'photo.image' => 'Please choose image file type.'
        ];
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ], $messages);

        // file upload
        $photoName = time().'.'.$request->photo->extension();  
        $request->photo->move(public_path('backend_template/brand_img/'), $photoName);
        $filePath = 'backend_template/brand_img/'.$photoName;

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->photo = $filePath;

        $brand->save();

        return new BrandResource($brand);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }
}

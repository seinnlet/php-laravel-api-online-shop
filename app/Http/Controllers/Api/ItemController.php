<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        // return ItemResource::collection($items);

        return response()->json([
            "status" => "ok",
            "totalResults" => count($items), 
            "items" => ItemResource::collection($items)
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
        $messages = [
            'codeno.required' => '* Please enter Item Code Number.',
            'name.required' => '* Please enter Item Name.',
            'photo.required' => '* Please choose Item Photo.',
            'price.required' => '* Please enter Item Price.',
            'brand_id.required' => '* Please choose Item Brand.',
            'subcategory_id.required' => '* Please choose Item Subcategory.',
            'photo.image' => 'Please choose image file type.',
            'discount.min' => 'Discount percentage should be greater than 0.',
            'discount.max' => 'Discount percentage should be less than 100.',
            'price.numeric' => 'Please enter number value for Price.',
            'price.min' => 'Item Price should be greater than 0.',
            'price.max' => 'Item Price should be greater than 1000000.',
            'brand_id.numeric' => '* Please choose Item Brand.',
            'subcategory_id.numeric' => '* Please choose Item Subcategory.'
        ];
        // validation
        $validatedData = $request->validate([
            'codeno' => 'required',
            'name' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'required|min:0|max:1000000|numeric',
            'discount' => 'nullable|min:0|max:100|numeric',
            'brand_id' => 'required|numeric',
            'subcategory_id' => 'required|numeric'
        ], $messages);

        // file upload
        $photoName = time().'.'.$request->photo->extension();  
        $request->photo->move(public_path('backend_template/item_img/'), $photoName);
        $filePath = 'backend_template/item_img/'.$photoName;

        // store data
        $item = new Item;
        $item->codeno = $request->codeno;
        $item->name = $request->name;
        $item->photo = $filePath;
        $item->price = $request->price;
        $item->discount = ($request->discount) ? $request->discount : 0;
        $item->description = $request->description;
        $item->brand_id = $request->brand_id;
        $item->subcategory_id = $request->subcategory_id;

        $item->save();

        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Subcategory;
use Illuminate\Http\Request;
use App\Http\Resources\SubcategoryResource;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategories = Subcategory::all();

        return response()->json([
            "status" => "ok",
            "totalResults" => count($subcategories), 
            "subcategories" => SubcategoryResource::collection($subcategories)
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
            'name.required' => '* Please enter Category Name.',
            'name.min' => 'Category Name should be 3 letters and more.',
            'category_id.required' => '* Please choose Item Category.',
            'category_id.numeric' => '* Please choose Item Category.'
        ];

        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'category_id' => 'required|numeric'
        ], $messages);

        $subcategory = new Subcategory;
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;

        $subcategory->save();

        return new SubcategoryResource($subcategory);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        return new SubcategoryResource($subcategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        //
    }
}

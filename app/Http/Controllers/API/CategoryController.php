<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response([ 'categories' => CategoryResource::collection( $categories ), 'message' => 'Retrieved successfully.'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make( $data, [
            'name'        => 'required|max:99',
            'description' => 'required|max:255'
        ] );

        if( $validator->fails() ) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $category = Category::create($data);

        return response(['category' => new CategoryResource($category), 'message' => 'Created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response(['category' => new CategoryResource($category), 'message' => 'Retrieved successfully.'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = Category::findOrFail($request->id);

        $category->update( $request->all() );

        return response(['category' => new CategoryResource($category), 'message' => 'Updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $msg = 'Unable to delete category.';

        $category = Category::findOrFail($request->id);
        
        if( $category->id && $category->delete() ) {
            $msg = 'Category deleted.';
        }

        return response(['message' => $msg]);
    }
}

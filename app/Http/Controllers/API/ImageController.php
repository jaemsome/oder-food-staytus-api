<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ImageResource;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::all();
        return response([ 'images' => ImageResource::collection( $images ), 'message' => 'Retrieved successfully.'], 200);
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
            'description' => 'required|max:255',
            'filename'    => 'required|max:99'
        ] );

        if( $validator->fails() ) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $image = Image::create($data);

        return response(['image' => new ImageResource($image), 'message' => 'Created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);

        return response(['image' => new ImageResource($image), 'message' => 'Retrieved successfully.'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $image = Image::findOrFail($request->id);

        $image->update( $request->all() );

        return response(['image' => new ImageResource($image), 'message' => 'Updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $msg = 'Unable to delete image.';

        $image = Image::findOrFail($request->id);
        
        if( $image->id && $image->delete() ) {
            $msg = 'Image deleted.';
        }

        return response(['message' => $msg]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        return response([ 'items' => ItemResource::collection( $items ), 'message' => 'Retrieved successfully.'], 200);
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
            'menu_id'     => 'sometimes|integer|nullable',
            'image_id'    => 'sometimes|integer|nullable',
            'categories'  => 'sometimes|array|nullable'
        ] );

        if( $validator->fails() ) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $item = Item::create( $data );
        $item->categories()->attach( $data['categories'] );


        return response(['item' => new ItemResource($item), 'categories' => $item->categories, 'message' => 'Created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);

        return response(['item' => new ItemResource($item), 'categories' => $item->categories, 'message' => 'Retrieved successfully.'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $item = Item::findOrFail( $request->id );

        $item->update( $request->all() );
        $item->categories()->sync( $request['categories'] );

        return response(['item' => new ItemResource($item), 'categories' => $item->categories, 'message' => 'Updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $msg = 'Unable to delete item.';

        $item = Item::findOrFail($request->id);
        
        if( $item->id && $item->categories()->sync([]) && $item->delete() ) {
            $msg = 'Item deleted.';
        }

        return response(['message' => $msg]);
    }
}

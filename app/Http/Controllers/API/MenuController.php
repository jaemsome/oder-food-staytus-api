<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MenuResource;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::all();
        return response([ 'menus' => MenuResource::collection( $menus ), 'message' => 'Retrieved successfully.'], 200);
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

        $menu = Menu::create($data);

        return response(['menu' => new MenuResource($menu), 'message' => 'Created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::findOrFail($id);

        return response(['menu' => new MenuResource($menu), 'message' => 'Retrieved successfully.'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $menu = Menu::findOrFail($request->id);

        $menu->update( $request->all() );

        return response(['menu' => new MenuResource($menu), 'message' => 'Updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $msg = 'Unable to delete menu.';

        $menu = Menu::findOrFail($request->id);
        
        if( $menu->id && $menu->delete() ) {
            $msg = 'Menu deleted.';
        }

        return response(['message' => $msg]);
    }
}

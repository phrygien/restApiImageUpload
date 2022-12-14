<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lister les produits
        $products = Product::all();

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
     
            // Create Product
            Product::create([
                'name' => $request->name,
                'image' => $imageName,
                'description' => $request->description
            ]);
     
            // Enregistrer l'Image dans Storage folder
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
     
            // Return Json Response
            return response()->json([
                'message' => "Produit bien enregistrĂ©."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Erreur!"
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Detail  du produit
       $product = Product::find($id);
       if(!$product){
         return response()->json([
            'message'=>'Produit Introuvable.'
         ],404);
       }
     
       // Return Json Response
       return response()->json([
          'product' => $product
       ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStoreRequest $request, $id)
    {
            try {
            // Find produit
            $product = Product::find($id);
            if(!$product){
              return response()->json([
                'message'=>'Produit Introuvable.'
              ],404);
            }
     
            $product->name = $request->name;
            $product->description = $request->description;
     
            if($request->image) {
                // Public storage
                $storage = Storage::disk('public');
     
                // Ancienne iamge delete
                if($storage->exists($product->image))
                    $storage->delete($product->image);
     
                // Image name
                $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
                $product->image = $imageName;
     
                // Image save in public folder
                $storage->put($imageName, file_get_contents($request->image));
            }
     
            // Update Produit
            $product->save();
     
            // Return Json Response
            return response()->json([
                'message' => "Modification bien enregistrĂ©."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Erreur!"
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Detail 
        $product = Product::find($id);
        if(!$product){
          return response()->json([
             'message'=>'Produit Introuvable.'
          ],404);
        }
     
        // Public storage
        $storage = Storage::disk('public');
     
        // Iamge delete
        if($storage->exists($product->image))
            $storage->delete($product->image);
     
        // Delete Produit
        $product->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "Produit bien supprimĂ©."
        ],200);
    }
}

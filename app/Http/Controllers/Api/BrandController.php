<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();

        if ($brands->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return BrandResource::collection($brands);
    }

    public function show($id)
    {
        $brand = Brand::where('id', $id)->get();

        if ($brand->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new BrandResource($brand->first());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'required|string',
            'description' => 'required|string',
            'about' => 'required|string',
            'address' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/brands', $image->hashName());
            Storage::delete('public/brands/' . basename($brand->image));

            $brand->update([
                'image' => $image->hashName(),
                'brand_name' => $request->brand_name,
                'description' => $request->description,
                'about' => $request->about,
                'address' => $request->address,
            ]);

        } else {
            $brand->update([
                'brand_name' => $request->brand_name,
                'description' => $request->description,
                'about' => $request->about,
                'address' => $request->address,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah brand',
        ]);

    }
}

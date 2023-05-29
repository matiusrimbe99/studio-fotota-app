<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::all();

        if ($galleries->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return GalleryResource::collection($galleries);
    }

    public function show($id)
    {
        $gallery = Gallery::where('id', $id)->get();

        if ($gallery->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new GalleryResource($gallery->first());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/galleries', $image->hashName());

        Gallery::create([
            'image' => $image->hashName(),
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan galeri',
        ]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/galleries', $image->hashName());
            Storage::delete('public/galleries/' . basename($gallery->image));

            $gallery->update([
                'image' => $image->hashName(),
                'description' => $request->description,
            ]);

        } else {
            $gallery->update([
                'description' => $request->description,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah galeri',
        ]);

    }

    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        Storage::delete('public/galleries/' . basename($gallery->image));

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus galeri',
        ]);

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudioResource;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::all();

        if ($studios->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return StudioResource::collection($studios);
    }

    public function show($id)
    {
        $studio = Studio::where('id', $id)->get();

        if ($studio->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new StudioResource($studio->first());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'studio_name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/studios', $image->hashName());

        Studio::create([
            'image' => $image->hashName(),
            'studio_name' => $request->studio_name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan studio',
        ]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'studio_name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $studio = Studio::find($id);

        if (!$studio) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/studios', $image->hashName());
            Storage::delete('public/studios/' . basename($studio->image));

            $studio->update([
                'image' => $image->hashName(),
                'studio_name' => $request->studio_name,
                'description' => $request->description,
                'price' => $request->price,
            ]);

        } else {
            $studio->update([
                'studio_name' => $request->studio_name,
                'description' => $request->description,
                'price' => $request->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah studio',
        ]);

    }

    public function destroy($id)
    {
        $studio = Studio::find($id);

        if (!$studio) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        Storage::delete('public/studios/' . basename($studio->image));

        $studio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus studio',
        ]);

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PacketResource;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PacketController extends Controller
{
    public function index()
    {
        $packets = Packet::all();

        if ($packets->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return PacketResource::collection($packets);
    }

    public function show($id)
    {
        $packet = Packet::where('id', $id)->get();

        if ($packet->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new PacketResource($packet->first());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'packet_name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/packets', $image->hashName());

        Packet::create([
            'image' => $image->hashName(),
            'packet_name' => $request->packet_name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan paket',
        ]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'packet_name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $packet = Packet::find($id);

        if (!$packet) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/packets', $image->hashName());
            Storage::delete('public/packets/' . basename($packet->image));

            $packet->update([
                'image' => $image->hashName(),
                'packet_name' => $request->packet_name,
                'description' => $request->description,
                'price' => $request->price,
            ]);

        } else {
            $packet->update([
                'packet_name' => $request->packet_name,
                'description' => $request->description,
                'price' => $request->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah paket',
        ]);

    }

    public function destroy($id)
    {
        $packet = Packet::find($id);

        if (!$packet) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        Storage::delete('public/packets/' . basename($packet->image));

        $packet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus paket',
        ]);

    }

}

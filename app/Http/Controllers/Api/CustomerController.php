<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);

        if ($customers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return CustomerResource::collection($customers);
    }

    public function show($id)
    {
        $customer = Customer::where('id', $id)->get();

        if ($customer->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new CustomerResource($customer->first());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|numeric',
            'address' => 'required|string',
            'nomor_hp' => 'required|string',
        ]);

        Customer::create([
            'image' => 'default.jpg',
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'nomor_hp' => $request->nomor_hp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data pelanggan',
        ]);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string',
            'gender' => 'required|numeric',
            'address' => 'required|string',
            'nomor_hp' => 'required|string',
        ]);

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/customers', $image->hashName());
            Storage::delete('public/customers/' . basename($customer->image));

            $customer->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'gender' => $request->gender,
                'address' => $request->address,
                'nomor_hp' => $request->nomor_hp,
            ]);
        } else {
            $customer->update([
                'name' => $request->name,
                'gender' => $request->gender,
                'address' => $request->address,
                'nomor_hp' => $request->nomor_hp,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah data pelanggan',
        ]);

    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        Storage::delete('public/customers/' . basename($customer->image));

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data pelanggan',
        ]);

    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderOrderedResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $codeOrder = 'ORD-' . date('YmdHis') . '-' . uniqid();
        $user = auth()->user();

        $order = $request->validate([
            'packet_id' => 'required|exists:packets,id',
            'studio_id' => 'required|exists:studios,id',
            'shooting_date' => 'required|date_format:Y-m-d',
        ]);

        $order['user_id'] = $user->id;
        $order['code_order'] = $codeOrder;
        $order['status_order_id'] = 2;

        Order::create($order);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan order',
        ]);
    }

    public function show($id)
    {
        $order = Order::where('id', $id)->get();

        if ($order->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new OrderDetailResource($order->first());
    }

    public function getOrderOrdered()
    {
        $orders = Order::where('status_order_id', 2)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return OrderOrderedResource::collection($orders);

    }
}

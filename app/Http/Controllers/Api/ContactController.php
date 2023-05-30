<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();

        if ($contacts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return ContactResource::collection($contacts);
    }

    public function show($id)
    {
        $contact = Contact::where('id', $id)->get();

        if ($contact->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return new contactResource($contact->first());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'account_number' => 'required|string',
            'name_on_account' => 'required|string',
            'bank_name' => 'required|string',
            'method_order' => 'required|string',
        ]);

        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $contact->update([
            'account_number' => $request->account_number,
            'name_on_account' => $request->name_on_account,
            'bank_name' => $request->bank_name,
            'method_order' => $request->method_order,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah kontak',
        ]);

    }
}

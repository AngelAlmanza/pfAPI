<?php

namespace App\Http\Controllers;

use App\Models\Veterinary;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Rules\Base64Image;

class VeterinaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $veterinaries = Veterinary::paginate(10);
        return response()->json(['veterinaries' => $veterinaries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'string|email',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'description' => 'string',
            'image' => ['nullable', new Base64Image()],
        ]);

        $base64Image = $request->input('image');
        if ($base64Image) {
            $image = Image::create([
                'base64_url' => $base64Image,
                'type'=> $request->input('image_type')
            ]);
        }

        $veterinary = Veterinary::create([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'description' => $request->input('description'),
        ]);

        if (isset($image)) {
            $veterinary->images()->attach($image->id);
        }
        return response()->json(['veterinary' => $veterinary], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $veterinary = Veterinary::findOrFail($id);
        $images = $veterinary->images->pluck('base64_url');
        return response()->json(['veterinary' => $veterinary]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'string|email',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'description' => 'string',
            'image' => ['nullable', new Base64Image()],
        ]);

        $base64Image = $request->input('image');
        if ($base64Image) {
            $image = Image::create([
                'base64_url' => $base64Image,
                'type'=> $request->input('image_type')
            ]);
        }

        $veterinary = Veterinary::findOrFail($id);

        $veterinary->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'description' => $request->input('description'),
        ]);

        if (isset($image)) {
            $veterinary->images()->attach($image->id);
        }

        return response()->json(['veterinary' => $veterinary], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        //
        $veterinary = Veterinary::findOrFail($id);
        $veterinary->delete();
        return response()->json(['message' => 'Veterinary deleted'], 200);
    }
}

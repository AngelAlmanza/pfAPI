<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Rules\Base64Image;

class ProfileController extends Controller
{
    //

    public function show()
    {
        $profile = Profile::findOrFail(Auth::id());
        return response()->json(["profile" => $profile]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "last_name" => "nullable|string",
            "city" => "nullable|string",
            "profile_picture" => ["nullable", new Base64Image()],
            "cover_picture" => ["nullable", new Base64Image()],
        ]);

        $profile = Profile::create($request->all());

        return response()->json($profile, 201);
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            "name" => "sometimes|required|string",
            "last_name" => "sometimes|required|string",
            "city" => "sometimes|required|string",
            "profile_picture" => ["sometimes", "required", new Base64Image()],
            "cover_picture" => ["sometimes", "required", new Base64Image()],
        ];

        $validatedData = $request->validate($rules);

        $profile = Profile::findOrFail(Auth::id());

        foreach ($validatedData as $key => $value) {
            $profile->$key = $value;
        }

        $profile->save();

        return response()->json($profile, 200);
    }

    public function delete()
    {
        $profile = Profile::findOrFail(Auth::id());
        $profile->delete();

        return response()->json(null, 204);
    }
}

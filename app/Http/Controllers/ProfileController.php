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

    public function updateName(Request $request)
    {
        $request->validate([
            "name" => "required|string",
        ]);

        $profile = Profile::findOrFail(Auth::id());
        $profile->name = $request->name;
        $profile->save();

        return response()->json($profile, 200);
    }

    public function updateLastName(Request $request)
    {
        $request->validate([
            "last_name" => "required|string",
        ]);

        $profile = Profile::findOrFail(Auth::id());
        $profile->last_name = $request->last_name;
        $profile->save();

        return response()->json($profile, 200);
    }

    public function updateCity(Request $request)
    {
        $request->validate([
            "city" => "required|string",
        ]);

        $profile = Profile::findOrFail(Auth::id());
        $profile->city = $request->city;
        $profile->save();

        return response()->json($profile, 200);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            "profile_picture" => ["required", new Base64Image()],
        ]);

        $profile = Profile::findOrFail(Auth::id());
        $profile->profile_picture = $request->profile_picture;
        $profile->save();

        return response()->json($profile, 200);
    }

    public function updateCoverPicture(Request $request)
    {
        $request->validate([
            "cover_picture" => ["required", new Base64Image()],
        ]);

        $profile = Profile::findOrFail(Auth::id());
        $profile->cover_picture = $request->cover_picture;
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

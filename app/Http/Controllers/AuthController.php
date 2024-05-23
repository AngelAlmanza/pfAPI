<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Dd;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "lastname" => "string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8|confirmed",
        ]);

        $user = User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $profile = Profile::create([
            "name" => $request->name,
            "lastname" => $request->lastname,
            "user_id" => $user->id,
        ]);

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "email" => ["The provided credentials are incorrect."],
            ]);
        }

        $token = $user->createToken("authToken")->plainTextToken;

        return response()->json(
            ["token" => $token, "user" => $user, "profile" => $profile],
            201
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();
        $profile = Profile::where("user_id", $user->id)->firstOrFail();

        if ($user->id == null) {
            return response()->json(["message" => "User not found"], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "email" => ["The provided credentials are incorrect."],
            ]);
        }

        $token = $user->createToken("authToken")->plainTextToken;

        return response()->json(
            ["token" => $token, "user" => $user, "profile" => $profile],
            200
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Logged out"], 200);
    }
}

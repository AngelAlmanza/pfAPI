<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Pet;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use App\Rules\Base64Image;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(["pet.images", "user.profile"])->paginate(10);
        return response()->json(["posts" => $posts]);
    }

    public function show($id)
    {
        $post = Post::with(["pet.images", "user.profile"])->findOrFail($id);
        return response()->json(["post" => $post]);
    }

    public function create(Request $request)
    {
        $request->validate([
            // Pet validations
            "pet.name" => "required|string|max:255",
            "pet.type" => "required|string",
            "pet.breed" => "required|string",
            "pet.age" => "required|string",
            "pet.personality" => "required|string",
            // "pet.image" => ["nullable", new Base64Image()],
            "pet.image" => "nullable|string",
            "pet.image_type" => "nullable|string",

            // Post validations
            "post.title" => "required|string|max:255",
            "post.content" => "required|string",
            "post.type" => "required|string",
            "post.location" => "nullable|string",
        ]);

        $base64Image = $request->input("pet.image");
        $image = null;

        if ($base64Image) {
            $image = Image::create([
                "base64_url" => $base64Image,
                "type" => $request->input("pet.image_type"),
            ]);
        }

        // Create the pet
        $pet = Pet::create([
            "name" => $request->input("pet.name"),
            "type" => $request->input("pet.type"),
            "breed" => $request->input("pet.breed"),
            "age" => $request->input("pet.age"),
            "personality" => $request->input("pet.personality"),
            "user_id" => Auth::id(),
        ]);

        // Attach the image to the pet if created
        if ($image) {
            $pet->images()->attach($image->id);
        }

        // Create the post
        $post = Post::create([
            "title" => $request->input("post.title"),
            "content" => $request->input("post.content"),
            "type" => $request->input("post.type"),
            "location" => $request->input("post.location"),
            "user_id" => Auth::id(),
            "pet_id" => $pet->id,
        ]);

        return response()->json(["pet" => $pet, "post" => $post], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "post.title" => "required|string|max:255",
            "post.content" => "required|string",
            "pet.name" => "required|string|max:255",
            "pet.type" => "required|string",
            "pet.breed" => "required|string",
            "pet.age" => "required|string",
            "pet.personality" => "required|string",
        ]);

        $post = Post::with("pet")->findOrFail($id);

        // Verificar si el usuario tiene permiso para actualizar el post y la pet
        // if (!Auth::user()->can('update any post') && $post->user_id !== Auth::id()) {
        //     return response()->json(['message' => 'Acción no autorizada'], 403);
        // }

        $post->update([
            "title" => $request->input("post.title"),
            "content" => $request->input("post.content"),
        ]);

        $post->pet->update([
            "name" => $request->input("pet.name"),
            "type" => $request->input("pet.type"),
            "breed" => $request->input("pet.breed"),
            "age" => $request->input("pet.age"),
            "personality" => $request->input("pet.personality"),
        ]);

        return response()->json(["post" => $post]);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $pet = Pet::findOrFail($post->pet_id);

        $pet->delete();
        $post->delete();
        return response()->json([
            "message" => "Post y Pet eliminado exitosamente",
        ]);
    }

    public function search(Request $request)
    {
        $query = Post::query();

        if ($request->has("title")) {
            $query->where(
                "title",
                "like",
                "%" . $request->input("title") . "%"
            );
        }

        if ($request->has("content")) {
            $query->where(
                "content",
                "like",
                "%" . $request->input("content") . "%"
            );
        }

        if ($request->has("animal-type")) {
            $query->whereHas("pet", function ($q) use ($request) {
                $q->where("type", $request->input("animal-type"));
            });
        }

        // Eager load pets and user profile relationships
        $posts = $query->with(["pet", "user.profile"])->paginate(10);

        return response()->json(["posts" => $posts]);
    }
}

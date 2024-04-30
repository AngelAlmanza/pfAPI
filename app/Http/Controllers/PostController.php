<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::paginate(10);
        return response()->json(['posts' => $posts]);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json(['post' => $post]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'pet.name' => 'required|string|max:255',
            'pet.type' => 'required|string',
            'pet.breed' => 'required|string',
            'pet.age' => 'required|string',
            'pet.personality' => 'required|string',
            'pet.image' => 'nullable|image',

            'post.title' => 'required|string|max:255',
            'post.content' => 'required|string',
            'post.type' => 'required|string',
            'post.location' => 'nullable|string',
        ]);

        $pet = Pet::create([
            'name' => $request->input('pet.name'),
            'type' => $request->input('pet.type'),
            'breed' => $request->input('pet.breed'),
            'age' => $request->input('pet.age'),
            'personality' => $request->input('pet.personality'),
            // 'image' => $request->file('pet.image')->store('pets', 'public'),
            'user_id' => Auth::id(),
        ]);

        $post = Post::create([
            'title' => $request->input('post.title'),
            'content' => $request->input('post.content'),
            'type' => $request->input('post.type'),
            'location' => $request->input('post.location'),
            'user_id' => Auth::id(),
            'pet_id' => $pet->id,
        ]);

        return response()->json(['pet' => $pet, 'post' => $post], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'post.title' => 'required|string|max:255',
            'post.content' => 'required|string',
            'pet.name' => 'required|string|max:255',
            'pet.type' => 'required|string',
            'pet.breed' => 'required|string',
            'pet.age' => 'required|string',
            'pet.personality' => 'required|string',
        ]);

        $post = Post::with('pet')->findOrFail($id);

        // // Verificar si el usuario tiene permiso para actualizar el post y la pet
        // if (!Auth::user()->can('update any post') && $post->user_id !== Auth::id()) {
        //     return response()->json(['message' => 'Acción no autorizada'], 403);
        // }

        $post->update([
            'title' => $request->input('post.title'),
            'content' => $request->input('post.content'),
        ]);

        $post->pet->update([
            'name' => $request->input('pet.name'),
            'type' => $request->input('pet.type'),
            'breed' => $request->input('pet.breed'),
            'age' => $request->input('pet.age'),
            'personality' => $request->input('pet.personality'),
        ]);

        return response()->json(['post & pet' => $post]);
    }


    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $pet = Pet::findOrFail($post->pet_id);

        $pet->delete();
        $post->delete();
        return response()->json(['message' => 'Post y Pet eliminado exitosamente']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['post' => $post], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($id);

        if (Auth::user()->can('update any post') || (Auth::user()->can('update own post') && $post->user_id === Auth::id())) {
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json(['post' => $post]);
        }

        return response()->json(['message' => 'Acción no autorizada'], 403);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if (Auth::user()->can('delete any post') || (Auth::user()->can('delete own post') && $post->user_id === Auth::id())) {
            $post->delete();
            return response()->json(['message' => 'Post eliminado exitosamente']);
        }

        return response()->json(['message' => 'Acción no autorizada'], 403);
    }
}

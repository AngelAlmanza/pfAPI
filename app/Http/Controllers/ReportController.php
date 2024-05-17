<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;

class ReportController extends Controller
{
    // Get all reports
    public function index()
    {
        return Report::all();
    }

    // Get a single report
    public function show($id)
    {
        return Report::findOrFail($id);
    }

    // Create a new report
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
            'is_resolved' => 'sometimes|boolean',
            'resolution' => 'sometimes|string',
            'resolved_at' => 'sometimes|date',
            'reported_at' => 'sometimes|date',
        ]);

        $post = Post::findOrFail($request->post_id);

        if ($post->reported) {
            return response()->json(['error' => 'This post has already been reported'], 400);
        }

        $report = Report::create($request->all());

        return response()->json($report, 201);
    }

    // Update an existing report
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
            'is_resolved' => 'sometimes|boolean',
            'resolution' => 'sometimes|string',
            'resolved_at' => 'sometimes|date',
            'reported_at' => 'sometimes|date',
        ]);

        $report = Report::findOrFail($id);
        $report->update($request->all());

        return response()->json($report, 200);
    }

    // Delete a report
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(null, 204);
    }
}

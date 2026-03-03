<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $query = Announcement::with('author');

        if ($request->published_only) {
            $query->published();
        }

        $announcements = $query->orderByDesc('created_at')->get();

        return response()->json($announcements);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // BelongsToTenant trait automatically sets institution_id
        $announcement = Announcement::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json($announcement->load('author'), 201);
    }

    public function show(Announcement $announcement): JsonResponse
    {
        $announcement->load('author');

        return response()->json($announcement);
    }

    public function update(Request $request, Announcement $announcement): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $announcement->update($request->only(['title', 'content']));

        return response()->json($announcement);
    }

    public function destroy(Announcement $announcement): JsonResponse
    {
        $announcement->delete();

        return response()->json(['message' => 'Comunicado eliminado']);
    }

    public function publish(Announcement $announcement): JsonResponse
    {
        $announcement->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return response()->json([
            'message' => 'Comunicado publicado',
            'announcement' => $announcement,
        ]);
    }
}

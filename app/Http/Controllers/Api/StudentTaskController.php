<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentTask;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentTaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function submit(Request $request, StudentTask $studentTask): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:20480',
        ]);

        $studentTask = $this->taskService->submit($studentTask, $request->file('file'));

        return response()->json($studentTask->load(['task', 'student.user']));
    }

    public function downloadSubmission(StudentTask $studentTask)
    {
        if (! $studentTask->submission_path) {
            return response()->json(['message' => 'Este estudiante no ha entregado el archivo'], 404);
        }

        $user = auth()->user();

        // Only teacher (who owns the task) or admin/coordinator can download
        if ($user->role === 'teacher') {
            $teacher = $user->teacher;
            if (! $teacher || $studentTask->task->teacher_id !== $teacher->id) {
                return response()->json(['message' => 'Sin acceso'], 403);
            }
        }

        return Storage::disk('public')->response(
            $studentTask->submission_path,
            $studentTask->submission_name ?? 'submission'
        );
    }

    public function review(StudentTask $studentTask): JsonResponse
    {
        $studentTask->update(['status' => 'reviewed']);

        return response()->json($studentTask->load(['student.user']));
    }
}

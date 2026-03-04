<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Task::with(['teacher.user', 'group.grade', 'subject'])
            ->withCount('studentTasks');

        // Teachers see only their own tasks
        if ($user->role === 'teacher' && $user->teacher) {
            $query->where('teacher_id', $user->teacher->id);
        }

        if ($request->group_id) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        $tasks = $query->orderByDesc('due_date')->get();

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'due_date' => 'required|date',
            'is_published' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $task = $this->taskService->create(
            $request->only(['teacher_id', 'group_id', 'subject_id', 'title', 'instructions', 'due_date', 'is_published']),
            $request->file('attachment')
        );

        return response()->json($task->load(['teacher.user', 'group.grade', 'subject']), 201);
    }

    public function show(Task $task): JsonResponse
    {
        $task->load([
            'teacher.user',
            'group.grade',
            'subject',
            'studentTasks.student.user',
        ]);

        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'instructions' => 'sometimes|string',
            'due_date' => 'sometimes|date',
            'subject_id' => 'nullable|exists:subjects,id',
            'is_published' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $task = $this->taskService->update(
            $task,
            $request->only(['title', 'instructions', 'due_date', 'subject_id', 'is_published']),
            $request->file('attachment')
        );

        return response()->json($task->load(['teacher.user', 'group.grade', 'subject']));
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->delete($task);

        return response()->json(['message' => 'Tarea eliminada correctamente']);
    }

    public function downloadAttachment(Task $task)
    {
        if (! $task->attachment_path) {
            return response()->json(['message' => 'Esta tarea no tiene archivo adjunto'], 404);
        }

        return Storage::disk('public')->response(
            $task->attachment_path,
            $task->attachment_name ?? 'attachment.pdf'
        );
    }
}

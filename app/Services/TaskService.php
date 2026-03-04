<?php

namespace App\Services;

use App\Models\StudentTask;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskService
{
    public function create(array $data, ?UploadedFile $attachment): Task
    {
        return DB::transaction(function () use ($data, $attachment) {
            if ($attachment) {
                $data['attachment_path'] = $attachment->store('tasks/attachments', 'public');
                $data['attachment_name'] = $attachment->getClientOriginalName();
            }

            $task = Task::create($data);

            $studentIds = $task->group->activeStudents()->pluck('id');

            $now = now();
            $rows = $studentIds->map(fn ($sid) => [
                'task_id' => $task->id,
                'student_id' => $sid,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            if (! empty($rows)) {
                StudentTask::insert($rows);
            }

            return $task;
        });
    }

    public function update(Task $task, array $data, ?UploadedFile $attachment): Task
    {
        if ($attachment) {
            if ($task->attachment_path) {
                Storage::disk('public')->delete($task->attachment_path);
            }
            $data['attachment_path'] = $attachment->store('tasks/attachments', 'public');
            $data['attachment_name'] = $attachment->getClientOriginalName();
        }

        $task->update($data);

        return $task;
    }

    public function delete(Task $task): void
    {
        // Delete all student submission files
        $task->studentTasks()->whereNotNull('submission_path')->each(function ($st) {
            Storage::disk('public')->delete($st->submission_path);
        });

        // Delete teacher attachment
        if ($task->attachment_path) {
            Storage::disk('public')->delete($task->attachment_path);
        }

        $task->delete();
    }

    public function submit(StudentTask $studentTask, UploadedFile $file): StudentTask
    {
        // Delete previous submission if re-submitting
        if ($studentTask->submission_path) {
            Storage::disk('public')->delete($studentTask->submission_path);
        }

        $path = $file->store('tasks/submissions', 'public');

        $studentTask->update([
            'status' => 'submitted',
            'submission_path' => $path,
            'submission_name' => $file->getClientOriginalName(),
            'submitted_at' => now(),
        ]);

        return $studentTask;
    }
}

<?php

namespace App\Services;

use App\Task;

class TaskService
{
    public function getIndexJson()
    {
        return Task::all()->map(function ($task) {
            return [
                'id' => $task->id,
                'assigned_to_organizations' => $task->assignments->pluck('assigned_to_organization_id')
            ];
        })->toJson();
    }
}

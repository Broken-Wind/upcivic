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
                'assigned_to_organizations' => [15, 16, 17]
            ];
        })->toJson();
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

class TaskRepository implements TaskRepositoryInterface
{
    private User $user;
    private Task $task;

    public function __construct(User $user, Task $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    public function getAll()
    {
        return $this->task::where(
            'user_id','=',auth()->user()->getAuthIdentifier()
        )->get();
        //return Task::with('user')->get();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $taskDetails): bool
    {
        $this->task->setAttribute('task_name', $taskDetails['task_name']);
        $this->task->setAttribute('task_description', $taskDetails['task_description']);
        $this->task->setAttribute('start_date', $taskDetails['start_date']);
        $this->task->setAttribute('deadline_date', $taskDetails['deadline_date']);
        $this->task->setAttribute('user_id', $taskDetails['user_id']);
        $this->task->setAttribute('status', $taskDetails['status']);

        return $this->task->save($taskDetails);
    }

    public function update($taskId, array $newDetails)
    {
        return $this->task->whereId($taskId)->update($newDetails);
    }

    public function getByDateInterval(array $filtered_date)
    {
        $filtered_date_field = $filtered_date['date_type'];
        $filtered_date_from = $filtered_date['initial_date'];
        $filtered_date_to = $filtered_date['end_date'];

        $tasks = $this->task::whereBetween(
            $filtered_date_field,
            [
                $filtered_date_from,
                $filtered_date_to
            ]
        )->get();

        return $tasks;
    }

    public function isDateAvaliable(Carbon $date): int
    {
        $date_field = 'start_date';
        $tasks = $this->task::where($date_field, '=', $date)->count();

        return $tasks;

    }

    public function destroy(int $id): int
    {
        return $this->task::destroy($id);
    }
}

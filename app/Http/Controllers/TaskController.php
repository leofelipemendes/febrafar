<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatus;
use App\Http\Requests\Api\StoreTaskResquest;
use App\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

class TaskController extends Controller
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilteredDate(Request $request): JsonResponse
    {
        $filtered_date = [
            'date_type' => $request->date_type,
            'initial_date' => $request->initial_date,
            'end_date' => $request->end_date
        ];

        $tasks = $this->taskRepository->getByDateInterval($filtered_date);

        return response()->json([
            'data' => $tasks
        ],Response::HTTP_ACCEPTED);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(StoreTaskResquest $request): JsonResponse
    {
        $request->merge([
            'user_id' => auth()->user()->getAuthIdentifier(),
            'status' => TaskStatus::OPEN
        ]);

        $task = $request->only([
            'task_name',
            'task_description',
            'start_date',
            'deadline_date',
            'user_id',
            'status'
        ]);

        $requested_date = Carbon::parse($request->start_date);
        $is_avaliable_date = $this->checkDateAvaliable($requested_date);

        if (!$is_avaliable_date) {
            return response()->json([
                'data' => 'Date ' . date_format(
                    $requested_date, 'Y-m-d'
                    ) . ' already in use.'
            ],Response::HTTP_OK);
        }

        $this->taskRepository->create($task);

        return response()->json([
            'data' => 'Task Created.'
        ],Response::HTTP_CREATED);
    }

    /**
     * Verify if a date is avaliable for new task
     *
     * @return \Illuminate\Http\Response
     */
    private function checkDateAvaliable(Carbon $date): bool
    {
        $tasks = $this->taskRepository->isDateAvaliable($date);
        if ($tasks > 0) {
            return false;
        }

        return true;
    }

    /**
     * Display all resource of the logged user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(): JsonResponse
    {
        $tasks = $this->taskRepository->getAll();

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (array_key_exists('completition_date', $request->toArray())) {
            $request->merge([
                'status' => TaskStatus::DONE
            ]);
        }

        $task = $request->only([
            'task_name',
            'task_description',
            'start_date',
            'deadline_date',
            'completition_date',
            'status'
        ]);

        if (!$this->taskRepository->update($id, $task)) {
            throw new \Exception('Error while update');
        }

        return response()->json([
            'data' => 'Task Update.'
            ],Response::HTTP_CREATED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;
        $deleted = $this->taskRepository->destroy($id);

        if ($deleted == 0) {
            return response()->json([
                'data' => 'Task Not Found.'
            ],Response::HTTP_ACCEPTED
            );
        }

        return response()->json([null],Response::HTTP_NO_CONTENT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\PriorityTaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="Task Manager API",
 *     version="1.0.0",
 *     description="REST API for managing tasks with prioritization based on deadline and importance."
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Task Management Endpoints"
 * )
 *
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"title", "status", "importance", "deadline"},
 *     @OA\Property(property="title", type="string", example="Prepare report"),
 *     @OA\Property(property="description", type="string", example="Quarterly summary"),
 *     @OA\Property(property="status", type="string", enum={"TODO", "IN_PROGRESS", "COMPLETED"}, example="TODO"),
 *     @OA\Property(property="importance", type="integer", example=4, minimum=1, maximum=5),
 *     @OA\Property(property="deadline", type="string", format="date-time", example="2025-04-22 18:00:00")
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter tasks by status (TODO, IN_PROGRESS, COMPLETED)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('status') && in_array($request->status, ['TODO', 'IN_PROGRESS', 'COMPLETED'])) {
            $query->where('status', $request->status);
        }

        return TaskResource::collection($query->orderByDesc('created_at')->paginate(15));
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(response=201, description="Task created")
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get a specific task by ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task found")
     * )
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(response=200, description="Task updated")
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Task deleted")
     * )
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/priority",
     *     summary="Get prioritized tasks",
     *     tags={"Tasks"},
     *     @OA\Response(response=200, description="List of prioritized tasks")
     * )
     */
    public function priority()
    {
        $tasks = Task::where('status', '!=', 'COMPLETED')->get();

        $prioritized = $tasks->sortByDesc(fn ($task) => $task->priority_score);

        return PriorityTaskResource::collection($prioritized->values());
    }
}

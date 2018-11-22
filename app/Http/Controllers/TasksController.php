<?php

namespace App\Http\Controllers;

use App\ItemsGroup;
use App\Service\Packing\ItemsGroupInput;
use App\Service\PackingService;
use App\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getTasks()
    {
        return JsonResponse::create(Task::with('groups')->get());
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getTask($id)
    {
        $task = Task::with('groups.items')->find($id);

        return JsonResponse::create($task);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTask($id, Request $request)
    {
        $groups = ItemsGroup::whereIn('id', $request->get('groups'))->get();
        $task = Task::with('groups.items')->find($id);
        if (!$task) $task = new Task();
        $task->volume = $request->get('volume');
        $task->title = $request->get('title');
        $task->save();
        $task->groups()->sync($groups);

        return JsonResponse::create($task);
    }

    /**
     * @param $id
     * @throws \App\Service\Packing\PackingException
     */
    public function runTask($id)
    {
        $task = Task::with('groups.items')->findOrFail($id);
        $packingService = new PackingService();
        collect([])->toArray();

        $inputGroups = [];
        foreach ($task->groups as $group) {
            $inputGroups[] = new ItemsGroupInput($group->items->all(), $group->min_count);
        }

        return JsonResponse::create(collect($packingService->loadBag($task->volume, $inputGroups)));
    }
}

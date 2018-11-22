<?php

namespace App\Http\Controllers;

use App\ItemsGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function getGroupsWithItemsAction()
    {
        return JsonResponse::create(ItemsGroup::with('items')->get());
    }
}

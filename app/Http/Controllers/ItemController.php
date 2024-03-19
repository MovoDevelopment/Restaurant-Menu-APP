<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Repositories\ItemRepository;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        $items = Item::query()->with('category')->get();
        return $this->sendResponse(ItemResource::collection($items), "Items List");
    }

    public function store()
    {

    }
}

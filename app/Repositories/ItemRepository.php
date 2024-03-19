<?php

namespace App\Repositories;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemRepository
{

    public function storeItem($categoryId, $name, $description, $price, $userId = null): Item
    {
        $item = new Item();
        if (Auth::user()->role != "admin")
            $item->user_id = Auth::user()->id;
        else
            $item->user_id = $userId;
        $item->category_id = $categoryId;
        $item->name = $name;
        $item->name = $name;
        $item->description = $description;
        $item->price = $price;
        $item->save();
        return $item;
    }

    public function deleteItem($id)
    {
        return Item::find($id)->delete();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'path', 'last_child'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getAllChildren()
    {
        return $this->children()->with('getAllChildren');
    }
}

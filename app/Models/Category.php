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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function getAllChildren()
    {
        return $this->children()->with('getAllChildren');
    }

    public function getChildCountAttribute()
    {
        if ($this->attributes['path'])
            return count(explode(',', $this->attributes['path']));
        return 0;
    }

    public function getChildAttribute()
    {
        if ($this->attributes['path'])
            return explode(',', $this->attributes['path']);
    }
}

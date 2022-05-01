<?php

namespace App\Models;

use App\Exceptions\ResourceNotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    public function products()
    {
        return $this->hasMany(Products::class);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_name',
        'category_description'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public static function findByIdOrFail(int $id): self
    {
        $category = Category::find($id);
        if (is_null($category)) {
            throw new ResourceNotFound('Category not found');
        }
        return $category;
    }
}

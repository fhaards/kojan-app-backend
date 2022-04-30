<?php

namespace App\Models;

use App\Exceptions\ResourceNotFound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = [
        'product_name',
        'category_id',
        'product_thumb',
        'product_description'
    ];

    protected $hidden = [];
    protected $casts = [];

    public static function findByIdOrFail(int $id): self
    {
        $product = Products::find($id);
        if (is_null($product)) {
            throw new ResourceNotFound('Product not found');
        }
        return $product;
    }
}

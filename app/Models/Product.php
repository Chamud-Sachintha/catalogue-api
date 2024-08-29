<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'category',
        'team_commision',
        'direct_commision',
        'is_store_pick',
        'waranty',
        'description',
        'supplier_name',
        'stock_count',
        'images',
        'status',
        'create_time'
    ];

    public function find_by_Cid($pid) {
        $map['category'] = $pid;

        return $this->where($map)->get();
    }

    public function findAllProducts() {
        return $this->all();
    }
}

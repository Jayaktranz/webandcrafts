<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_name', 'order_id', 'phone_number', 'order_date'];

    public function products(){
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id', 'id');
    }
}

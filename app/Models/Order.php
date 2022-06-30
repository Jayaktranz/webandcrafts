<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_fname', 'customer_lname', 'phone_number', 'order_date', 'total_amount', 'order_id',];

    public function products(){
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
                     ->withPivot('quantity');
    }
}

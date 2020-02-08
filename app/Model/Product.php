<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    
    protected $primaryKey = 'product_row_id';  

    protected $fillable = [
        'product_price',
    ];
	
	public function category_info()
    {
        return $this->belongsTo('App\Models\Category', 'category_row_id', 'category_row_id');
    }
	
}

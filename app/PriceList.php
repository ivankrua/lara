<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $fillable = ['provider', 'brand', 'location', 'cpu', 'drive', 'price'];
    protected $visible = ['id', 'provider', 'brand', 'location', 'cpu', 'drive', 'price'];

    protected $hidden = ['created_at', 'updated_at'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportCache extends Model
{
    protected $fillable = ['recordid'];
    protected $visible = ['id', 'recordid'];
}

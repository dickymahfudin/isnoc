<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NojsUser extends Model
{
    protected $primaryKey = 'nojs';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ServiceCall extends Model
{
    protected $primaryKey = 'service_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
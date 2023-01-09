<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function rule()
    {
        return $this->belongsTo(\App\Models\Rule::class);
    }
}

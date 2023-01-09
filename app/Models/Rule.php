<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function policy()
    {
        return $this->belongsTo(\App\Models\Policy::class);
    }

    public function log()
    {
        return $this->hasMany(\App\Models\Log::class);
    }
}

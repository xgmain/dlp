<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function rule()
    {
        return $this->hasMany(\App\Models\Rule::class);
    }

    public static function rules(Collection $policies)
    {
        $rules = collect();
        $policies->each(function($policy) use($rules) {
            $policy->rule->each(function($rule) use($rules) {
                $rules->push($rule);
            });
        });

        return $rules;
    }
}

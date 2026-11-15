<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = ['message', 'page', 'lu_at'];

    protected function casts(): array
    {
        return ['lu_at' => 'datetime'];
    }
}

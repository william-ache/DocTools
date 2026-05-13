<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
     protected $fillable = ['name', 'icon', 'color', 'content', 'is_active'];

}

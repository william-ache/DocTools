<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
     protected $fillable = ['tenant_id', 'name', 'icon', 'color', 'content', 'is_active'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

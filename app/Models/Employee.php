<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position',
        'email',
        'phone',
        'salary',
        'is_active'
    ];


    public function payments()
    {
        return $this->hasMany(EmployeePayment::class);
    }
}

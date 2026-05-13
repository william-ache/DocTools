<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePayment extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'payment_date',
        'concept',
        'reference',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTimeLog extends Model
{
    use HasFactory;

    protected $fillable = ['employeeId', 'startTime','breakTime', 'endTime', 'hoursWorked', 'breakSum', 'totalWorkedHours'];
    protected $casts = [
        'breakTime' => 'array'
    ];
}

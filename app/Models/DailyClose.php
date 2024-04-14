<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyClose extends Model
{
    use HasFactory;

    protected $fillable = ['closeDay', 'remainCash', 'cardSum', 'cashSum', 'profit', 'totalNumberOfCustomers'];
    protected $casts = [
        'remainCash' => 'array'
    ];
}

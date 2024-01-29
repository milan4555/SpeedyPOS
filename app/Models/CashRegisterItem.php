<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegisterItem extends Model
{
    use HasFactory;

    protected $fillable = ['productIdReg', 'cashRegisterNumber', 'howMany', 'updated_at'];
}

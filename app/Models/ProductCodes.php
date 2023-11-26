<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productCodes extends Model
{
    use HasFactory;
    protected $fillable = ['productIdCode', 'productCode'];
}

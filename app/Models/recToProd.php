<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class recToProd extends Model
{
    use HasFactory;
    protected $fillable = ['productId', 'receiptId', 'quantity', 'atTimePrice'];
}

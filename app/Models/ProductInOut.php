<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInOut extends Model
{
    use HasFactory;
    protected $fillable = ['productId', 'howMany', 'inOrOut', 'newBPrice'];
}

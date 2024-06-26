<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRight extends Model
{
    use HasFactory;

    protected $primaryKey = 'rightsId';
    protected $fillable = ['isSuperior', 'canCreateProduct', 'canDeleteProduct', 'canUpdateProduct'];
}

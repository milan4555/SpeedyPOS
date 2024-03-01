<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUnit extends Model
{
    use HasFactory;
    protected $primaryKey = 'storageId';

    protected $fillable = ['storageName', 'numberOfRows', 'widthNumber', 'heightNumber'];
}

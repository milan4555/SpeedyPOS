<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $primaryKey = 'companyId';
    protected $fillable = ['companyName', 'postcode', 'city', 'street', 'streetNumber', 'isSupplier', 'taxNumber', 'owner', 'phoneNumber'];
}

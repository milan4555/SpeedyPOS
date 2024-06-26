<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['receiptSerialNumber', 'isInvoice', 'date', 'change', 'sumPrice', 'employeeId', 'paymentType'];
    protected $primaryKey = 'receiptId';
}

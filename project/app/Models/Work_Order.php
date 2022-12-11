<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_Order extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'work_order';
    protected $fillable = ['work_order_number', 'external_id', 'priority', 'received_date', 'category', 'fin_loc'];
}

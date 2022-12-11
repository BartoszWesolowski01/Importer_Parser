<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importer_Log extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'importer_logs';
    protected $fillable = ['entries_created', 'entries_processed'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbdb_medications';

    protected $fillable = [
        'customer_id',
        'name',
        'description',
        'quantity'
    ];
}

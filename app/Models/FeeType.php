<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $table = 'fee_types';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'start_date',
        'end_date'
    ];

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_fee_type', 'fee_type_id', 'class_id');
    }
}

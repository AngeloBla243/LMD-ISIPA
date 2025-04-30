<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisSubmissionSetting extends Model
{
    use HasFactory;


    protected $fillable = ['class_id', 'opening_date', 'closing_date'];

    public function classe()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'question_text', 'type', 'points'];

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}

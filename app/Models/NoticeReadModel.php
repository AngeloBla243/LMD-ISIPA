<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeReadModel extends Model
{
    protected $table = 'notice_reads';
    public $timestamps = false;

    protected $fillable = [
        'notice_board_id',
        'user_id',
        'read_at'
    ];
}

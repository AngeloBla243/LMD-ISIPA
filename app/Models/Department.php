<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name'];

    // Relation : un département a plusieurs utilisateurs (agents/étudiants)
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'score', 'result',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

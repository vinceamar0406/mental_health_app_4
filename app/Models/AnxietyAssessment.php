<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnxietyAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'responses',
        'impact',
        'severity',
        'total_score',
        'created_at',

    ];

    protected $casts = [
        'responses' => 'array', // Automatically convert JSON responses to an array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

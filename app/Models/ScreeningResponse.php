<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningResponse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'responses', 'predicted_condition'];

    protected $casts = [
        'responses' => 'array', // Ensure JSON storage works correctly
    ];
}

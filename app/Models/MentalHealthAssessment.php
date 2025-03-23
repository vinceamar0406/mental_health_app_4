<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalHealthAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessment_type',
        'responses',
        'total_score',
        'severity',
        'impact',
    ];

    protected $casts = [
        'responses' => 'array', // Store responses as JSON in the database
    ];


    // Relationship: Each assessment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
{
    return $this->hasOne(Appointment::class, 'assessment_id');
}

}

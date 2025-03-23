<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MentalHealthAssessment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StressAssessmentController extends Controller
{
    // Show the anxiety assessment page
    public function index()
    {
        return Inertia::render('Assessment/StressAssessment');
    }

    // Store assessment results in the database
    public function storeStress(Request $request)
    {
        $request->validate([
            'responses' => 'required|array|min:10',
        ]);

        $responses = $request->responses;

        // Reverse the scores for questions 4, 5, 7, and 8
        $responses[3] = 4 - $responses[3]; // Question 4
        $responses[4] = 4 - $responses[4]; // Question 5
        $responses[6] = 4 - $responses[6]; // Question 7
        $responses[7] = 4 - $responses[7]; // Question 8

        $totalScore = array_sum($responses);

        // Determine severity and impact
        $severity = 'Low';
        $impact = 'Not difficult at all';

        if ($totalScore >= 14 && $totalScore <= 26) {
            $severity = 'Moderate';
            $impact = 'Somewhat difficult';
        } elseif ($totalScore >= 27) {
            $severity = 'High';
            $impact = 'Very difficult';
        }
        // Store the anxiety assessment result in the database
        MentalHealthAssessment::create([
            'user_id' => Auth::id(),
            'assessment_type' => 'Stress', // Identify the type of assessment
            'responses' => json_encode($request->responses),
            'impact' => $request->impact,
            'total_score' => $request->total_score,
            'severity' => $request->severity,
        ]);

        // Redirect to the results page
        return redirect()->route('assessment.stress.results');
    }

    // Fetch past assessments for the results page
    public function showStressResults()
    {
        $assessments = MentalHealthAssessment::where('user_id', Auth::id())
            ->where('assessment_type', 'stress') // Filter by anxiety assessments
            ->latest()
            ->get();

        $latestResult = $assessments->first();

        return Inertia::render('Assessment/StressResults', [
            'latest_result' => $latestResult,
            'past_results' => $assessments->skip(1)->map(function ($assessment) {
                return [
                    'total_score' => $assessment->total_score,
                    'severity' => $assessment->severity,
                    'date' => $assessment->created_at->format('F j, Y'), // Format the date properly
                ];
            })->values(),
        ]);
    }
}

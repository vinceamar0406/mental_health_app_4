<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MentalHealthAssessment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EatingDisorderAssessmentController extends Controller
{
    // Show the anxiety assessment page
    public function index()
    {
        return Inertia::render('Assessment/EatingDisorderAssessment');
    }

    // Store assessment results in the database
    public function storeEatingDisorder(Request $request)
    {
        $request->validate([
            'responses' => 'required|array|min:5',
            'impact' => 'required|string',
            'total_score' => 'required|integer',
            'severity' => 'required|string',
        ]);
        // Store the anxiety assessment result in the database
        MentalHealthAssessment::create([
            'user_id' => Auth::id(),
            'assessment_type' => 'Eating Disorder', // Identify the type of assessment
            'responses' => json_encode($request->responses),
            'impact' => $request->impact,
            'total_score' => $request->total_score,
            'severity' => $request->severity,
        ]);

        // Redirect to the results page
        return redirect()->route('assessment.eatingdisorder.results');
    }

    // Fetch past assessments for the results page
    public function showEatingDisorderResults()
    {
        $assessments = MentalHealthAssessment::where('user_id', Auth::id())
            ->where('assessment_type', 'eating disorder') // Filter by anxiety assessments
            ->latest()
            ->get();

        $latestResult = $assessments->first();

        return Inertia::render('Assessment/EatingDisorderResults', [
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

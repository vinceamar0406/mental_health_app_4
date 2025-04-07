<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\MentalHealthAssessment;

class AssessmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $assessments = MentalHealthAssessment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('AssessmentHub', [
            'assessments' => $assessments
        ]);
    }

    // ✅ Assessment Hub - Displays available severity assessment options
    public function hub()
    {
        return Inertia::render('AssessmentHub', [
            'assessments' => [
                ['name' => 'Anxiety Assessment', 'route' => 'anxiety_assessment'],
                ['name' => 'Depression Assessment', 'route' => 'depression_assessment'],
                ['name' => 'PTSD Assessment', 'route' => 'ptsd_assessment'],
                ['name' => 'Stress-Related Disorder Assessment', 'route' => 'stress_assessment'],
                ['name' => 'Substance Use Disorder Assessment', 'route' => 'substance_use_assessment'],
                ['name' => 'Eating Disorder Assessment', 'route' => 'eating_disorder_assessment'],
                ['name' => 'Self-Harm Challenges Assessment', 'route' => 'self_harm_assessment'],
                ['name' => 'Attention Issues Assessment', 'route' => 'attention_issues_assessment'],
            ],
        ]);
    }

    // ✅ Individual assessment methods
    public function anxiety()
    {
        return Inertia::render('Assessment/AnxietyAssessment');
    }

    public function depression()
    {
        return Inertia::render('Assessment/DepressionAssessment');
    }

    public function ptsd()
    {
        return Inertia::render('Assessment/PTSDAssessment');
    }

    public function stress()
    {
        return Inertia::render('Assessment/StressAssessment');
    }

    public function substanceUse()
    {
        return Inertia::render('Assessment/SubstanceUseAssessment');
    }

    public function eatingDisorder()
    {
        return Inertia::render('Assessment/EatingDisorderAssessment');
    }

    public function selfHarm()
    {
        return Inertia::render('Assessment/SelfHarmAssessment');
    }

    public function attentionIssues()
    {
        return Inertia::render('Assessment/AttentionIssuesAssessment');
    }

    // ✅ Store Anxiety Assessment Responses & Redirect
    // ✅ Store Anxiety Assessment Responses & Redirect
public function storeAnxiety(Request $request)
{
    $request->validate([
        'responses' => 'required|array|min:9',
        'impact' => 'required|string',
        'total_score' => 'required|integer',
        'severity' => 'required|string',
    ]);

    $assessment = MentalHealthAssessment::create([
        'user_id' => Auth::id(),
        'assessment_type' => 'anxiety', // Identify the type of assessment
        'responses' => json_encode($request->responses),
        'impact' => $request->impact,
        'total_score' => $request->total_score,
        'severity' => $request->severity,
    ]);

    // Redirect to the results page
    return redirect()->route('assessment.anxiety.results');
}
    // ✅ Store Depression Assessment Responses & Redirect
    public function storeDepression(Request $request)
    {
        $validatedData = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'integer|min:1|max:4',
        ]);

        $user = auth()->user();
        $totalScore = array_sum($validatedData['responses']);
        $severity = $this->determineSeverity($totalScore, 'Depression');

        $assessment = MentalHealthAssessment::create([
            'user_id' => $user->id,
            'assessment_type' => 'Depression',
            'responses' => json_encode($validatedData['responses']),
            'total_score' => $totalScore,
            'severity' => $severity['level'],
            'impact' => $severity['impact'],
        ]);

        return $assessment
            ? redirect('/assessment/depression/results')
            : redirect()->route('depression_assessment')->with('error', 'Failed to save assessment.');
    }
    public function storePTSD(Request $request)
{
    $validatedData = $request->validate([
        'responses' => 'required|array',
        'responses.*' => 'integer|min:1|max:4',
    ]);

    $user = auth()->user();
    $totalScore = array_sum($validatedData['responses']);
    $severity = $this->determineSeverity($totalScore, 'PTSD');

    $assessment = MentalHealthAssessment::create([
        'user_id' => $user->id,
        'assessment_type' => 'PTSD',
        'responses' => json_encode($validatedData['responses']),
        'total_score' => $totalScore,
        'severity' => $severity['level'],
        'impact' => $severity['impact'],
    ]);

    return $assessment
        ? redirect('/assessment/ptsd/results')
        : redirect()->route('ptsd_assessment')->with('error', 'Failed to save assessment.');
}
public function storeEatingDisorder(Request $request)
    {
        $request->validate([
            'responses' => 'required|array|min:5',
            'total_score' => 'required|integer',
            'severity' => 'required|string',
            'impact' => 'required|string',
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


public function storeSubstanceUse(Request $request)
{
    $request->validate([
        'responses' => 'required|array|min:5',
        'total_score' => 'required|integer',
        'severity' => 'required|string',
        'impact' => 'required|string',
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
    return redirect()->route('assessment.substance-use.results');
}

    public function storeStress(Request $request)
{
    $request->validate([
        'responses' => 'required|array|min:10|max:10',
    ]);

    $responses = $request->responses;

    // Reverse scoring for specific questions (if applicable)
    $responses[3] = 4 - $responses[3];
    $responses[4] = 4 - $responses[4];
    $responses[6] = 4 - $responses[6];
    $responses[7] = 4 - $responses[7];

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

    // ✅ Store in the database
    MentalHealthAssessment::create([
        'user_id' => Auth::id(),
        'assessment_type' => 'Stress',
        'responses' => json_encode($responses),
        'total_score' => $totalScore,
        'severity' => $severity,
        'impact' => $impact,
    ]);

    return redirect()->route('assessment.stress.results');
}



    // ✅ Show Anxiety Assessment Results
    public function showAnxietyResults()
{
    return $this->showResults('Anxiety', 'Assessment/AnxietyResults');
}
    // ✅ Show Depression Assessment Results
    public function showDepressionResults()
    {
        return $this->showResults('Depression', 'Assessment/DepressionResults');
    }
    public function showPTSDResults()
    {
        return $this->showResults('PTSD', 'Assessment/PTSDResults');
    }
    public function showEatingDisorder()
    {
        return $this->showResults('Eating Disorder', 'Assessment/EatingDisorderResults');
    }

    public function showStressResults()
{
    return $this->showResults('Stress', 'Assessment/StressResults');
}
public function showSubstanceUse()
{
    return $this->showResults('Substance-Use', 'Assessment/SubstanceUseResults');
}

    // ✅ Generic function to fetch latest & past results for an assessment type
    private function showResults($type, $view)
    {
        $user = auth()->user();
        $latestAssessment = MentalHealthAssessment::where('user_id', $user->id)
            ->where('assessment_type', $type)
            ->latest()
            ->first();

        if (!$latestAssessment) {
            return redirect()->route(strtolower($type) . '_assessment')
                ->with('error', 'No assessment results found.');
        }

        // Get all past assessments of this type
        $pastAssessments = MentalHealthAssessment::where('user_id', $user->id)
            ->where('assessment_type', $type)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render($view, [
            'total_score' => $latestAssessment->total_score ?? 'N/A',
            'severity' => $latestAssessment->severity ?? 'Unknown',
            'impact' => $latestAssessment->impact ?? 'Not provided',
            'latestAssessment' => $latestAssessment,
            'pastAssessments' => $pastAssessments,
        ]);
    }



    // ✅ Fetch User's Past Assessments for Dashboard (excluding PTSD to prevent duplication)
    public function history()
{
    $user = auth()->user();
    $assessments = MentalHealthAssessment::where('user_id', $user->id)
        ->whereIn('assessment_type', ['Anxiety', 'Depression', 'PTSD' , 'Eating Disorder', 'Stress', 'Substance-Use'])
        ->with('appointment') // Load related appointment data
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($assessment) {
            return [
                'id' => $assessment->id,
                'assessment_type' => $assessment->assessment_type,
                'total_score' => $assessment->total_score,
                'severity' => $assessment->severity,
                'impact' => $assessment->impact,
                'created_at' => $assessment->created_at,
                'appointment_status' => optional($assessment->appointment)->status ?? 'none',
                'appointment_date' => optional($assessment->appointment)->appointment_date,
            ];
        });

    return Inertia::render('AssessmentHistory', [
        'assessments' => $assessments,
    ]);
}

    public function getAssessments()
{
    $user = Auth::user();

    $assessments = MentalHealthAssessment::where('user_id', $user->id)
        ->with(['appointment']) // Load related appointment if it exists
        ->get()
        ->map(function ($assessment) {
            return [
                'id' => $assessment->id,
                'assessment_type' => $assessment->assessment_type,
                'total_score' => $assessment->total_score,
                'severity' => $assessment->severity,
                'impact' => $assessment->impact,
                'created_at' => $assessment->created_at,
                'appointment_status' => $assessment->appointment->status ?? 'none',
                'appointment_date' => optional($assessment->appointment)->appointment_date,
            ];
        });

    return Inertia::render('AssessmentHistory', ['assessments' => $assessments]);
}


    // ✅ Determine severity level based on type & score
    private function determineSeverity($score, $type)
    {
        if ($type === 'Anxiety') {
            if ($score <= 10) {
                return ['level' => 'Minimal Anxiety', 'impact' => 'Little to no impact on daily life'];
            } elseif ($score <= 20) {
                return ['level' => 'Mild Anxiety', 'impact' => 'Occasional distress, manageable'];
            } elseif ($score <= 30) {
                return ['level' => 'Moderate Anxiety', 'impact' => 'Frequent distress, affects daily routine'];
            } else {
                return ['level' => 'Severe Anxiety', 'impact' => 'Significant distress, major impact on daily life'];
            }
        } elseif ($type === 'Depression') {
            if ($score <= 10) {
                return ['level' => 'Minimal Depression', 'impact' => 'Little to no impact on daily life'];
            } elseif ($score <= 20) {
                return ['level' => 'Mild Depression', 'impact' => 'Occasional distress, manageable'];
            } elseif ($score <= 30) {
                return ['level' => 'Moderate Depression', 'impact' => 'Frequent distress, affects daily routine'];
            } else {
                return ['level' => 'Severe Depression', 'impact' => 'Significant distress, major impact on daily life'];
            }
        }

        return ['level' => 'Unknown', 'impact' => 'Not provided'];
    }
}

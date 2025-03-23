<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScreeningResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ScreeningController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'responses' => 'required|array|min:5',
            'responses.*' => 'required|string|min:10', // Ensure each response is meaningful
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Store responses in the database (JSON encode responses)
        $screeningResponse = ScreeningResponse::create([
            'user_id' => Auth::id(),
            'responses' => json_encode($request->input('responses')),
        ]);

        // Send responses to Flask API
        $flaskApiUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000/predict');
        \Log::info('Sending data to Flask:', ['responses' => $request->input('responses')]);
        $response = Http::post($flaskApiUrl, ['responses' => $request->input('responses')]);
        \Log::info('Flask API Response:', ['response' => $response->json()]);


        // Handle API failure
        if (!$response->successful()) {
            \Log::error('Flask API Error:', ['response' => $response->body()]);
            return response()->json(['error' => 'Failed to get a valid response from the Flask API.'], 500);
        }

        // Extract predicted condition
        $predictedCondition = $response->json()['predicted_condition'] ?? 'No result available';

        // Update the database with predicted condition
        $screeningResponse->update([
            'predicted_condition' => $predictedCondition,
        ]);

        // Redirect to result page
        return redirect()->route('screening.result');
    }

    public function showResult()
    {
        // Get latest screening response
        $screeningResponse = ScreeningResponse::where('user_id', Auth::id())->latest()->first();

        // Ensure we have a valid result
        $predictedCondition = $screeningResponse ? $screeningResponse->predicted_condition : 'No result available.';

        // Return Inertia response with the result
        return Inertia::render('ScreeningResult', [
            'message' => 'Screening responses saved successfully.',
            'predicted_condition' => $predictedCondition,
        ]);
    }
}

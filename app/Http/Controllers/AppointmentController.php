<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\MentalHealthAssessment;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    // Request an Appointment
    public function requestAppointment(Request $request)
{
    $user = Auth::user();
    $assessmentId = $request->assessment_id;

    // Check if an appointment already exists for this assessment and user
    $existingAppointment = Appointment::where('user_id', $user->id)
        ->where('assessment_id', $assessmentId)
        ->where('status', 'pending')
        ->first();

    if ($existingAppointment) {
        // If the appointment already exists, return to the assessment history page with a status message
        return Inertia::render('AssessmentHistory', [
            'message' => 'Appointment already requested.',
            'appointment_status' => 'pending', // Send the status to update the UI
            'appointments' => Appointment::where('user_id', $user->id)->get(),
        ]);
    }

    // Create a new appointment if no existing request is found
    $appointment = new Appointment();
    $appointment->user_id = $user->id;
    $appointment->assessment_id = $assessmentId;
    $appointment->status = 'pending';
    $appointment->save();

    // Return an Inertia response with the success message and updated appointment status
    return Inertia::render('AssessmentHistory', [
        'message' => 'Appointment requested successfully!',
        'appointments' => Appointment::where('user_id', $user->id)->get(),
    ]);
}
    // Approve an Appointment (Admin)
    public function approveAppointment(Request $request, Appointment $appointment)
{
    // Ensure only admins can approve
    if (Auth::user()->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized access.'], 403);
    }

    // Validate the incoming request to ensure the scheduled date is provided and valid
    $request->validate([
        'scheduled_at' => 'required|date|after:today', // Ensure it's a valid future date
    ]);

    // Set the appointment status to approved and store the scheduled date
    $appointment->status = 'approved';
    $appointment->appointment_date = $request->scheduled_at; // This should save the date in the appointment_date field
    $appointment->save();
    return response()->json([
        'message' => 'Appointment approved and scheduled successfully.',
        'appointment' => $appointment, // Return the updated appointment with the scheduled date
    ]);
}
public function completeAppointment(Appointment $appointment)
{
    // Ensure only admins can complete the appointment
    if (Auth::user()->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized access.'], 403);
    }

    // Check if the appointment is already completed
    if ($appointment->status === 'completed') {
        return response()->json(['error' => 'Appointment is already completed.'], 400);
    }

    // Mark the appointment as completed and set the completion date
    $appointment->status = 'completed';
    $appointment->completed_at = now(); // or use any other relevant date field
    $appointment->save();

    // Return the updated appointment data along with a success message
    return response()->json([
        'message' => 'Appointment marked as completed successfully.',
        'appointment' => $appointment,  // Return the updated appointment data
    ]);
}


}


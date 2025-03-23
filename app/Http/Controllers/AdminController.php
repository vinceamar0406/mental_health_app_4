<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Appointment;
use App\Models\Report;

class AdminController extends Controller
{
    // ✅ Admin Dashboard
    public function index(): Response
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized'); // Prevent non-admin access
        }

        $totalUsers = User::count(); // Fetch total users

        return Inertia::render('AdminHome', [
            'totalUsers' => $totalUsers // Pass data to Vue component
        ]);
    }

    // ✅ Fetch Total Users
    public function users()
{
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized'); // Prevent non-admin access
    }

    $users = User::all(); // Fetch all users

    return Inertia::render('UserList', [ 'users' => $users]);

}


    // ✅ Fetch Total Assessments
    public function assessments()
{
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Fetch all assessments with user details and related appointments
    $assessments = Assessment::with('user', 'appointment')->get()->map(function ($assessment) {
        return [
            'id' => $assessment->id,
            'assessment_type' => $assessment->assessment_type,
            'total_score' => $assessment->total_score,
            'severity' => $assessment->severity,
            'impact' => $assessment->impact,
            'created_at' => $assessment->created_at,
            'appointment_status' => $assessment->appointment ? $assessment->appointment->status : 'none',
            'appointment_date' => $assessment->appointment ? $assessment->appointment->appointment_date : null
        ];
    });



    return Inertia::render('AssessmentTaken', [
        'assessments' => $assessments
    ]);
}


    // ✅ Fetch Total Appointments
    public function appointments()
{
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Fetch all appointments with related user and assessment details
    $appointments = Appointment::with(['user', 'assessment'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'user' => [
                    'id' => $appointment->user->id,
                    'name' => $appointment->user->name,
                    'email' => $appointment->user->email,
                ],
                'assessment' => [
                    'id' => $appointment->assessment->id,
                    'type' => $appointment->assessment->assessment_type,
                    'severity' => $appointment->assessment->severity,
                ],
                'status' => $appointment->status,
                'appointment_date' => $appointment->appointment_date,
                'created_at' => $appointment->created_at->format('Y-m-d H:i'),
            ];
        });

    return Inertia::render('Appointments', [
        'appointments' => $appointments
    ]);
}

    // ✅ Fetch Total Reports
    public function reports()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return response()->json(['totalReports' => Report::count()]);
    }
}

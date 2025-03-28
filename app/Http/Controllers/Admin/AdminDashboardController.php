<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentalHealthAssessment;
use App\Models\User;
use App\Models\Appointment;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Notification;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        // Fetch notifications for the admin user
        $notifications = auth()->user()->notifications();// Get latest notifications

        // Fetch data for the dashboard
        $totalUsers = User::count();
        $totalAssessments = MentalHealthAssessment::count();
        $totalAppointments = Appointment::count();

        // Fetch recent activity
        $recentActivities = DB::table('appointments')
            ->join('users', 'appointments.user_id', '=', 'users.id')
            ->select('users.name as user', 'appointments.created_at as date', DB::raw("'Created Appointment' as action"))
            ->orderBy('appointments.created_at', 'desc')
            ->limit(5)
            ->union(
                DB::table('mental_health_assessments')
                    ->join('users', 'mental_health_assessments.user_id', '=', 'users.id')
                    ->select('users.name as user', 'mental_health_assessments.created_at as date', DB::raw("'Completed Assessment' as action"))
                    ->orderBy('mental_health_assessments.created_at', 'desc')
                    ->limit(5)
            )
            ->union(
                DB::table('screening_responses')
                    ->join('users', 'screening_responses.user_id', '=', 'users.id')
                    ->select('users.name as user', 'screening_responses.created_at as date', DB::raw("'Submitted Response' as action"))
                    ->orderBy('screening_responses.created_at', 'desc')
                    ->limit(5)
            )
            ->orderBy('date', 'desc')
            ->get();

        // Format the date before sending it to the frontend
        $recentActivities = $recentActivities->map(function ($activity) {
            $activity->formatted_date = Carbon::parse($activity->date)->format('F j, Y');
            return $activity;
        });

        $assessments = MentalHealthAssessment::with('user')
            ->select('id', 'user_id', 'assessment_type', 'total_score', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('AdminHome', [
            'totalUsers' => $totalUsers,
            'totalAssessments' => $totalAssessments,
            'totalAppointments' => $totalAppointments,
            'recentActivities' => $recentActivities,
            'assessments' => $assessments,
            'notifications' => $notifications,
        ]);
    }
}

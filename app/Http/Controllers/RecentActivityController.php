<?php

namespace App\Http\Controllers;

use App\Models\RecentActivity;

class RecentActivityController extends Controller
{
    public function index()
    {
        $activities = RecentActivity::with('user')->latest()->take(5)->get();
        return response()->json($activities);
    }
}

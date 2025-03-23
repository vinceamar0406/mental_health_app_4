<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnxietyAssessmentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DepressionAssessmentController;
use App\Http\Controllers\EatingDisorderAssessmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PTSDAssessmentController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\StressAssessmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\UserInteractionController;
use App\Http\Controllers\VideoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');

// ✅ Unified Dashboard (Users & Admins)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

    // ✅ Mental Health Screening (Initial Prediction)
    Route::get('/screening', [PredictionController::class, 'index'])->name('mental_health_screening');
    Route::get('/mental-health-screening', fn() => redirect()->route('mental_health_screening'));

    // ✅ Save responses before sending to Flask
    Route::post('/screening/submit', [ScreeningController::class, 'store'])->name('screening.submit');
    Route::get('/screening/result', [ScreeningController::class, 'showResult'])->name('screening.result');

    // ✅ Assessment & Evaluation (Severity Assessment)
    Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment');
    Route::get('/assessment/history', [AssessmentController::class, 'history'])->name('assessment.history');
    Route::get('/assessment-hub', [AssessmentController::class, 'hub'])->name('assessment_hub');

    // ✅ Individual Assessment Routes
    Route::prefix('assessment')->group(function () {
        Route::get('/anxiety', [AnxietyAssessmentController::class, 'index'])->name('anxiety_assessment');
        Route::get('/depression', [DepressionAssessmentController::class, 'index'])->name('depression_assessment');
        Route::get('/ptsd', [PTSDAssessmentController::class, 'index'])->name('ptsd_assessment');
        Route::get('/stress-related', [StressAssessmentController::class, 'index'])->name('stress_assessment');
        Route::get('/substance-use', [AssessmentController::class, 'substanceUse'])->name('substance_use_assessment');
        Route::get('/eating-disorder', [EatingDisorderAssessmentController::class, 'index'])->name('eating_disorder_assessment');
        Route::get('/self-harm', [AssessmentController::class, 'selfHarm'])->name('self_harm_assessment');
        Route::get('/attention-issues', [AssessmentController::class, 'attentionIssues'])->name('attention_issues_assessment');
    });

    // ✅ Store and Retrieve Assessment Results
    Route::post('/assessment/anxiety/store', [AnxietyAssessmentController::class, 'storeAnxiety'])->name('anxiety.assessment.store');
    Route::get('/assessment/anxiety/results', [AnxietyAssessmentController::class, 'showAnxietyResults'])->name('assessment.anxiety.results');

    Route::post('/assessment/depression/store', [DepressionAssessmentController::class, 'storeDepression'])->name('depression.assessment.store');
    Route::get('/assessment/depression/results', [DepressionAssessmentController::class, 'showDepressionResults'])->name('assessment.depression.results');

    Route::post('/assessment/ptsd/store', [PTSDAssessmentController::class, 'storePTSD'])->name('ptsd.assessment.store');
    Route::get('/assessment/ptsd/results', [PTSDAssessmentController::class, 'showPTSDResults'])->name('assessment.ptsd.results');

    Route::post('/assessment/stress-related/store', [StressAssessmentController::class, 'storeStress'])->name('stress.assessment.store');
    Route::get('/assessment/stress-related/results', [StressAssessmentController::class, 'showStressResults'])->name('assessment.stress.results');

    Route::post('/assessment/eating-disorder/store', [EatingDisorderAssessmentController::class, 'storeEatingDisorder'])->name('eatingdisorder.assessment.store');
    Route::get('/assessment/eating-disorder/results', [EatingDisorderAssessmentController::class, 'showEatingDisorderResults'])->name('assessment.eatingdisorder.results');


});

// ✅ Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/home', function () {
        return Inertia::render('AdminHome', [
            'totalUsers' => \App\Models\User::count(),
            'totalAssessments' => \App\Models\Assessment::count(),
            'totalAppointments' => \App\Models\Appointment::count(),
            'totalReports' => \App\Models\Report::count(),
        ]);
    })->name('admin.home');

    // ✅ User List Page
    Route::get('/users', function () {
        return Inertia::render('UserList', [
            'users' => \App\Models\User::all()
        ]);
    })->name('admin.users');

    // ✅ Assessment Taken Page
    Route::get('/assessments', function () {
        return Inertia::render('AssessmentTaken', [
            'assessments' => \App\Models\Assessment::with('user')->get()
        ]);
    })->name('admin.assessments');


    // ✅ Other Admin Pages
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('admin.appointments');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});
Route::post('/appointments/request', [AppointmentController::class, 'requestAppointment'])->name('appointments.request');
Route::post('/appointments/{appointment}/approve', [AppointmentController::class, 'approveAppointment'])->name('appointments.approve');
Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'completeAppointment'])->name('appointments.complete');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Contact Form Submission
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// ✅ Authentication Routes
require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CvProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CvTemplateController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Google OAuth
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');

// CV management (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/cv/profile', [CvProfileController::class, 'edit'])->name('cv.profile.edit');
    Route::patch('/cv/profile', [CvProfileController::class, 'update'])->name('cv.profile.update');

    Route::resource('cv/experiences', ExperienceController::class)
        ->except(['show'])
        ->names('cv.experiences');
    Route::resource('cv/certificates', CertificateController::class)
        ->except(['show'])
        ->names('cv.certificates');

    Route::resource('cv/educations', EducationController::class)
        ->except(['show'])
        ->names('cv.educations');
    Route::resource('cv/skills', SkillController::class)
        ->except(['show'])
        ->names('cv.skills');
    Route::resource('cv/languages', LanguageController::class)
        ->except(['show'])
        ->names('cv.languages');

    Route::resource('cv/cvs', CvController::class)
        ->except(['show'])
        ->names('cvs');

    Route::get('/cv/preview/{cv?}', [CvController::class, 'preview'])->name('cv.preview');
    Route::get('/cv/pdf/{cv?}', [CvController::class, 'pdf'])->name('cv.pdf');

    Route::resource('cv/templates', CvTemplateController::class)
        ->names('cv.templates');
    Route::post('cv/templates/preview', [CvTemplateController::class, 'preview'])
        ->name('cv.templates.preview');
});

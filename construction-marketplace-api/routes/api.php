<?php

use App\Modules\Job\JobRequestController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\Translation\TranslationController;
use App\Http\Controllers\User\UserController;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\JobCategory\JobCategoryController;
use App\Modules\Language\LanguageController;
use App\Modules\Location\CityController;
use App\Modules\Location\CountryController;
use App\Modules\Translation\CityTranslationController;
use App\Modules\RoomType\RoomTypeController;
use App\Modules\JobHistory\JobHistoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
// Route::middleware('auth:sanctum')->group(function () {
// Auth routes
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me']);
Route::get('/auth/tokens', [AuthController::class, 'tokens']);
Route::delete('/auth/tokens/{tokenId}', [AuthController::class, 'revokeToken']);

// User routes
// Route::get('/users', [UserController::class, 'index']);
// Route::get('/users/{id}', [UserController::class, 'show']);
// Route::put('/users/{id}', [UserController::class, 'update']);
// Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Job Request routes
Route::get('/job-requests', [JobRequestController::class, 'listAllJobRequests']);
Route::get('/job-requests/{jobRequest}', [JobRequestController::class, 'getJobRequestById']);
Route::post('/job-requests', [JobRequestController::class, 'createJobRequest']);
Route::put('/job-requests/{jobRequest}', [JobRequestController::class, 'updateJobRequest']);
Route::delete('/job-requests/{jobRequest}', [JobRequestController::class, 'deleteJobRequest']);

// Job routes
// Route::get('/jobs', [JobController::class, 'index']);
// Route::get('/jobs/{id}', [JobController::class, 'show']);
// Route::post('/jobs', [JobController::class, 'store']);
// Route::put('/jobs/{id}', [JobController::class, 'update']);
// Route::delete('/jobs/{id}', [JobController::class, 'destroy']);

// Provider routes
// Route::get('/providers', [ProviderController::class, 'index']);
// Route::get('/providers/{id}', [ProviderController::class, 'show']);
// Route::get('/providers/{id}/gallery', [ProviderController::class, 'gallery']);
// Route::post('/providers/{id}/shortlist', [ProviderController::class, 'shortlist']);

// Location routes
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}/lang/{languageCode}', [CountryController::class, 'showWithLanguage'])->where('code', '[A-Z]{2}');
Route::get('/countries/{code}/name/{languageCode}', [CountryController::class, 'getName'])->where('code', '[A-Z]{2}');
Route::get('/countries/{code}', [CountryController::class, 'show'])->where('code', '[A-Z]{2}');
Route::post('/countries', [CountryController::class, 'store']);
Route::put('/countries/{code}', [CountryController::class, 'update']);
Route::delete('/countries/{code}', [CountryController::class, 'destroy']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('/cities/country/{countryCode}/lang/{languageCode}', [CityController::class, 'getByCountryAndLanguage']);
Route::get('/cities/{id}/lang/{languageCode}', [CityController::class, 'showWithLanguage']);

// Language routes
Route::get('/languages', [LanguageController::class, 'index']);
Route::get('/languages/default', [LanguageController::class, 'default']);
Route::get('/languages/search/{searchTerm}', [LanguageController::class, 'search']);
Route::get('/languages/code/{code}', [LanguageController::class, 'showByCode']);
Route::get('/languages/{id}', [LanguageController::class, 'show']);
Route::post('/languages', [LanguageController::class, 'store']);
Route::put('/languages/{id}', [LanguageController::class, 'update']);
Route::delete('/languages/{id}', [LanguageController::class, 'destroy']);

// Translation routes
// Route::get('/translations', [TranslationController::class, 'index']);
// Route::get('/translations/{id}', [TranslationController::class, 'show']);
// Route::post('/translations', [TranslationController::class, 'store']);
// Route::put('/translations/{id}', [TranslationController::class, 'update']);
// Route::delete('/translations/{id}', [TranslationController::class, 'destroy']);

// Job Category routes
Route::get('/job-categories', [JobCategoryController::class, 'index']);
Route::get('/job-categories/lang/{languageCode}', [JobCategoryController::class, 'getAllWithLanguage']);
Route::get('/job-categories/{id}', [JobCategoryController::class, 'show']);
Route::get('/job-categories/code/{code}', [JobCategoryController::class, 'showByCode']);
Route::get('/job-categories/code/{code}/lang/{languageCode}', [JobCategoryController::class, 'showWithLanguage']);
Route::get('/job-categories/{id}/name/{languageCode}', [JobCategoryController::class, 'getName']);
Route::post('/job-categories', [JobCategoryController::class, 'store']);
Route::put('/job-categories/{id}', [JobCategoryController::class, 'update']);
Route::delete('/job-categories/{id}', [JobCategoryController::class, 'destroy']);

// Room Type routes
Route::get('/room-types', [RoomTypeController::class, 'index']);
Route::get('/room-types/lang/{languageCode}', [RoomTypeController::class, 'getAllWithLanguage']);
Route::get('/room-types/{id}', [RoomTypeController::class, 'show']);
Route::get('/room-types/code/{code}', [RoomTypeController::class, 'showByCode']);
Route::get('/room-types/code/{code}/lang/{languageCode}', [RoomTypeController::class, 'showWithLanguage']);
Route::get('/room-types/{id}/name/{languageCode}', [RoomTypeController::class, 'getName']);
Route::post('/room-types', [RoomTypeController::class, 'store']);
Route::put('/room-types/{id}', [RoomTypeController::class, 'update']);
Route::delete('/room-types/{id}', [RoomTypeController::class, 'destroy']);

// Job History routes
Route::get('/jobs/{jobId}/history', [JobHistoryController::class, 'getByJobId']);
Route::get('/jobs/{jobId}/history/paginated', [JobHistoryController::class, 'getByJobIdPaginated']);
Route::get('/jobs/{jobId}/history/latest', [JobHistoryController::class, 'getLatestForJob']);
Route::get('/jobs/{jobId}/history/status-changes', [JobHistoryController::class, 'getStatusChanges']);
Route::get('/jobs/{jobId}/history/timeline', [JobHistoryController::class, 'getTimelineSummary']);
Route::get('/job-history/{id}', [JobHistoryController::class, 'getById']);
Route::get('/job-history/action/{action}', [JobHistoryController::class, 'getByAction']);
Route::get('/job-history/user/{userId}', [JobHistoryController::class, 'getByUserId']);
Route::get('/job-history/action-types', [JobHistoryController::class, 'getActionTypes']);
// });

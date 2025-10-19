<?php

use App\Events\NewEvent;
use App\Http\Controllers\ProfileController;
use App\Jobs\newJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jobs', function () {
    $batch = Bus::batch([
        new newJob(),
    ])->dispatch();
});


Route::get('/cache', function () {
    if (Cache::has('key')) {
        return Cache::get('key');
    }

    Cache::add('key', 'value');
    return Cache::get('key');
});


Route::get('/dumps', function () {
    return dump('hello from the dump');
});

Route::get('/event', function () {
    NewEvent::dispatch();
});

Route::get('/exception', function () {
    throw new Exception("this is an exception");
});


Route::get('/gates', function () {
    if (Gate::forUser(Auth::user())->allows('testGate')) {
        return 'you are allowed to take this action';
    }
    abort(403);
});

Route::get('/http', function () {
    return Http::get('http://example.com');
});

Route::get('/logs', function () {
    Log::info("hello from the logs info level");;
});














Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

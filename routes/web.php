<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Mail\Timetable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::resource('markers', MarkerController::class);
    Route::post('comments-add/{post}', [CommentController::class, 'store'])->name('comments.add');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');

});

Route::get('/mailable', function () {
    // 1) Algus- ja lõpukuupäevad (Carbon objektid)
    $startDate = now()->startOfWeek();
    $endDate = now()->endOfWeek();

    // 2) Päringu jaoks ISO stringid
    $from = $startDate->toISOString();
    $thru = $endDate->toISOString();

    // 3) Tee sama HTTP päring nagu käsus
    $response = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
        'from' => $from,
        'lang' => 'ET',
        'page' => 0,
        'schoolId' => 38,
        'size' => 50,
        'studentGroups' => '39248890-7051-4182-9a9a-8a82259b862b',
        'thru' => $thru,
    ]);

    $data = $response->json();

    // 4) Sama Collection loogika mis TimetableNotification handle() sees
    $events = collect(data_get($data, 'content', []));

    $sorted = $events->sortBy([
        ['date', 'asc'],
        ['timeStart', 'asc'],
    ]);

    $grouped = $sorted->groupBy(function ($event) {
        $date = Carbon::parse(data_get($event, 'date'))->locale('et');
        return $date->dayName;
    });

    $timetableEvents = $grouped->map(function ($eventsForDay, $dayName) {
        $date = Carbon::parse(data_get($eventsForDay->first(), 'date'))->locale('et');

        return [
            'date' => $date->translatedFormat('d, F, Y'),
            'day' => $dayName,
            'entries' => $eventsForDay->map(function ($event) {
                return [
                    'name' => data_get($event, 'nameEt'),
                    'timeStart' => data_get($event, 'timeStart'),
                    'timeEnd' => data_get($event, 'timeEnd'),
                    'room' => data_get($event, 'rooms.0.roomCode'),
                ];
            })->values()->all(),
        ];
    });

    // 5) Tagasta mailable (Timetable ootab: Collection, Carbon, Carbon)
    return new Timetable($timetableEvents, $startDate, $endDate);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/authors.php';
require __DIR__.'/posts.php';
// require __DIR__.'/comments.php';



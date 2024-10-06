<?php

use App\Http\Controllers\LairageController;
use App\Http\Controllers\ProfileController;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseReceiptController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //Lairage

Route::get('/lairage', [LairageController::class,'index'])->name('lairage');


Route::get('/purchaseOrders', [PurchaseOrderController::class, 'index']);
Route::post('/purchaseOrders', [PurchaseOrderController::class, 'store']);
Route::get('/purchaseOrders/{order}', [PurchaseOrderController::class, 'show']);
Route::put('/purchaseOrders/{order}', [PurchaseOrderController::class, 'update']);
Route::delete('/purchaseOrders/{order}', [PurchaseOrderController::class, 'destroy']);

Route::get('/new-order-number', function () {
    $orderNumber = 'ORD-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    return response()->json(['orderNumber' => $orderNumber]);
});


Route::get('/purchaseReceipts', [PurchaseReceiptController::class, 'index']);
Route::post('/purchaseReceipts', [PurchaseReceiptController::class, 'store']);
Route::get('/purchaseReceipts/{receipt}', [PurchaseReceiptController::class, 'show']);
Route::put('/purchaseReceipts/{receipt}', [PurchaseReceiptController::class, 'update']);
Route::delete('/purchaseReceipts/{receipt}', [PurchaseReceiptController::class, 'destroy']);

Route::get('/new-receipt-number', function () {
    $orderNumber = 'ORD-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    return response()->json(['receiptNumber' => $orderNumber]);
});

});

Route::get('/logo', function () {
    $path = storage_path('app/public/img/fcllogo.png');

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});




require __DIR__.'/auth.php';

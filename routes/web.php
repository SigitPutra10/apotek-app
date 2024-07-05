<?php

use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('IsGuest')->group(function (){
      
    Route::get('/', function () {
        return view('login');
    })->name('login');
    
    Route::post('/login',[UserController::class,'authLogin'])->name('auth-login'); 
});

route::middleware('IsLogin')->group(function(){
route::get('/logout',[UserController::class, 'logout'])->name('auth-logout');
Route::get('/dashboard', function () {
    return view('dashboard');
});

route::middleware("IsAdmin")->group(function(){
    Route::prefix('/medicine')->name('medicine.')->group(function(){
        Route::get('/data', [MedicineController::class, 'index'])->name('data');
        Route::get('/create', [MedicineController::class, 'create'])->name('create');
        Route::post('/store', [MedicineController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [MedicineController::class, 'update'])->name('update');
        Route::delete('/delete/{id}',[MedicineController::class, 'destroy'])->name('delete');
        Route::get('/data/stock', [MedicineController::class, 'stockData'])->name('data.stock');
        Route::get('/{id}', [MedicineController::class, 'show'])->name('show');
        Route::patch('/stock/update/{id}', [MedicineController::class, 'updateStock'])->name('stock.update');
    });
    Route::prefix('/admin/order')->name('admin.order.')->group(function() {
        Route::get('/', [OrderController::class, 'data'])->name('data');
        Route::get('/download-excel', [OrderController::class, 'downloadExcel'])->name('download-excel');
        Route::get('/cari', [OrderController::class, 'cari'])->name('cari');
    });
    
    Route::prefix('/user')->name('user.')->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('home');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}',[UserController::class, 'destroy'])->name('delete');
        });
    });
});

Route::middleware('IsKasir')->group(function(){
    Route::prefix('/order')->name('order.')->group(function() {
        Route::get('/',[OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::get('/struk/{id}', [OrderController::class, 'strukPembelian'])->name('struk');
        Route::get('/download-pdf/{id}', [OrderController::class, 'downloadPDF'])->name('download-pdf');
        Route::get('/search', [OrderController::class, 'search'])->name('search');
    });
});




//prefix : awalan (mengelompokan url path sesuai dengan fiturnya)
//name prefix :awalan name route pada kelompok url path
// group : mengelompokan url path
//::get -> menampilkan halaman, ::post -> menambahkan data ke db, ::patch -> mengubah data ke db,::delete -> menghapus data ke db
//namaController::class,'namefunction'

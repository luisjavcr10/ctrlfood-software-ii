<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ReporteController;
use App\Models\Detail;

Route::get('/', function () {
    return redirect('home');
});


Auth::routes();

Route::get('/home', [HomeController::class, 'index']);


Route::group(['middleware'=>'auth'], function(){
    Route::resource('users', UserController::class);
    Route::put('users.update_foto/{id}', [UserController::class, 'updateFoto'])->name('users.update_foto');
    Route::put('users.update_password/{id}', [UserController::class, 'updatePassword'])->name('users.update_password');

    Route::resource('products', ProductController::class);
    Route::get('product_list', [ProductController::class, 'product_list']);

    Route::resource('clients', ClientController::class, ['except'=>'show']);
    Route::get('clients_list/{nit}', [ClientController::class, 'clients_list']);

    Route::resource('sales', SaleController::class, ['except' => ['edit', 'update']]);
    Route::delete('sale_delete/{id}', [SaleController::class, 'sale_delete']);

    Route::resource('details', DetailController::class, ['only' => 'store']);

    Route::get('print_recibo/{id}', [ReporteController::class, 'print_recibo']);
    Route::get('reporte_economico', [ReporteController::class, 'reporte_economico']);
    Route::get('reporte_estadistico', [ReporteController::class, 'reporte_estadistico']);

});

Route::get('prueba', function(){
   return response()->json([
       'message' => 'Ruta de prueba funcionando - Laravel 8.x',
       'status' => 'success',
       'laravel_version' => app()->version(),
       'php_version' => PHP_VERSION,
       'database' => 'PostgreSQL Connected',
       'timestamp' => now()->toDateTimeString()
   ]);
});

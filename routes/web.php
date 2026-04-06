<?php

// method lain
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// controller menu
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\DatabaseMaterialController;
use App\Http\Controllers\IncomingRawMaterialController;
use App\Http\Controllers\IncomingComplementMaterialController;
use App\Http\Controllers\ComplementBasedNoteController;
use App\Http\Controllers\ComplementBasedMaterialController;
use App\Http\Controllers\PurchaseBasedNoteController;
use App\Http\Controllers\PurchaseBasedRollController;
use App\Http\Controllers\PurchaseBasedYardController;
use App\Http\Controllers\ListAccountingEstimateController;
use App\Http\Controllers\ListSupplierEstimateController;
use App\Http\Controllers\ListColorEstimateController;
use App\Http\Controllers\ListUnitMeasureEstimateController;
use App\Http\Controllers\Lvl1TypeMaterialController;
use App\Http\Controllers\Lvl2TypeMaterialController;
use App\Http\Controllers\Lvl3TypeMaterialController;
use App\Http\Controllers\UnitInternalController;
use App\Http\Controllers\UnitSupplierController;
use App\Http\Controllers\WagesEstimateController;
use App\Http\Controllers\WorksheetAbbreviationController;
use App\Http\Controllers\DataWarehouseController;
use App\Http\Controllers\CategoryExpenseController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockRawMaterialController;
use App\Http\Controllers\StockComplementMaterialController;
use App\Http\Controllers\DataExpenseController;
use App\Http\Controllers\SummaryExpenseController;
use App\Http\Controllers\CoordinatorCodeController;
use App\Http\Controllers\AreaCodeController;
use App\Http\Controllers\TailorCodeController;


// verifikasi controller
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

// excel controller
use App\Http\Controllers\Api\ExcelDataController;
use App\Http\Controllers\DataTailorController;
use App\Http\Controllers\Exports\ExportController;
use App\Models\StockComplementMaterial;

use Illuminate\Support\Facades\Artisan;

// welcome
Route::get('/', function () {
    return view('welcome');
});

// verifikasi di admin
Route::get('/verify-admin/{id}/{hash}', [EmailVerificationNotificationController::class, 'verifyAdmin'])->name('verify.admin');

// verifikasi di user
Route::post('/send-verification-to-admin', [EmailVerificationNotificationController::class, 'sendVerification'])->middleware('auth')->name('send.verification');

// verifikasi di form login
Route::post('/resend-verification', [EmailVerificationNotificationController::class, 'resendVerification'])->name('resend.verification');

// dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Export semua tabel
    Route::get('/export-all', [ExportController::class, 'exportAll'])->name('export.all');

    // export excel tertentu
    Route::get('/export/{table}', [ExportController::class, 'export']);

    // Export Summary Expenses
    Route::get('/export-summary-expenses', [ExportController::class, 'exportSummaryExpenses']);

    // Route clear cache
    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');
        return back()->with('status', 'Cache aplikasi berhasil dibersihkan!');
    })->name('clear.cache');
});


// excel data realtime
Route::get('/excel-data', function (Request $request) {
    // Ambil header Authorization dari request
    $credentials = $request->header('Authorization');

    // Periksa apakah header Authorization ada dan formatnya Basic
    if (!$credentials || !str_starts_with($credentials, 'Basic ')) {
        return response()->json(['error' => 'Missing Credentials'], 401);
    }

    // Extract string setelah "Basic " dan decode dari Base64
    $decoded = base64_decode(substr($credentials, 6));

    // Pisahkan username dan password dari format "username:password"
    [$username, $password] = explode(':', $decoded, 2);

    // Ambil credential valid dari environment
    $validUser = env('EXCEL_USERNAME');
    $validPass = env('EXCEL_PASSWORD');

    // Validasi: cek apakah username dan password cocok
    // Hash::check() membandingkan password plain text dengan hash yang disimpan
    if ($username !== $validUser || !Hash::check($password, $validPass)) {
        return response()->json(['error' => 'Invalid Credentials'], 401);
    }

    // Panggil controller jika valid
    return app(ExcelDataController::class)->getAllData($request);

})->name('excel.data');

// auth middleware agar route hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);

    // admin
    Route::prefix('admin')->group(function () {
        // database materials (database bahan)
        Route::get('/api/database-materials/data', [DatabaseMaterialController::class, 'getAllData']);
        Route::resource('database-materials', DatabaseMaterialController::class);

        // incoming raw materials (input bahan baku)
        Route::get('/api/incoming-raw-materials/data', [IncomingRawMaterialController::class, 'getAllData']);
        Route::resource('incoming-raw-materials', IncomingRawMaterialController::class);

        // purchase based notes (pembelian berdasarkan nota BB)
        Route::get('/api/purchase-based-notes/data', [PurchaseBasedNoteController::class, 'getNotes']);
        Route::resource('purchase-based-notes', PurchaseBasedNoteController::class)->except(['edit', 'update']);

        // purchase based rolls (pembelian berdasarkan roll BB)
        Route::get('/api/purchase-based-rolls/data', [PurchaseBasedRollController::class, 'getRolls']);
        Route::resource('purchase-based-rolls', PurchaseBasedRollController::class);

        // purchase based yards (pembelian berdasarkan yard BB)
        Route::get('/api/purchase-based-yards/data', [PurchaseBasedYardController::class, 'getYards']);
        Route::resource('purchase-based-yards', PurchaseBasedYardController::class);

        // incoming complement materials (input bahan pelengkap)
        Route::get('/api/incoming-complement-materials/data', [IncomingComplementMaterialController::class, 'getAllData']);
        Route::resource('incoming-complement-materials', IncomingComplementMaterialController::class);

        // complement based notes (nota pelengkap)
        Route::get('/api/complement-based-notes/data', [ComplementBasedNoteController::class, 'getNotes']);
        Route::resource('complement-based-notes', ComplementBasedNoteController::class)->except(['edit', 'update']);

        // complement based materials (nama bahan pelengkap)
        Route::get('/api/complement-based-materials/data', [ComplementBasedMaterialController::class, 'getMaterials']);
        Route::resource('complement-based-materials', ComplementBasedMaterialController::class);

        // stock opnames
        Route::get('/api/stock-opnames/data', [StockOpnameController::class, 'dataGudang']);
        Route::get('/api/stock-opnames/materials', [StockOpnameController::class, 'getMaterials'])->name('api.stock-opnames.materials');
        Route::resource('stock-opnames', StockOpnameController::class);

        // stock raw materials (SPBB)
        Route::get('/api/stock-raw-materials/data', [StockRawMaterialController::class, 'getAllData']);
        Route::resource('stock-raw-materials', StockRawMaterialController::class)->except(['edit', 'update']);

        // stock complement materials (SPBP)
        Route::get('api/stock-complement-materials/data', [StockComplementMaterialController::class, 'getAllData']);
        Route::resource('stock-complement-materials', StockComplementMaterialController::class)->except(['edit', 'update']);

        // data expense (Data Pengeluaran)
        Route::get('api/data-expenses/data', [DataExpenseController::class, 'getAllData']);
        Route::resource('data-expenses', DataExpenseController::class);

        // summary expense (Rekapan Pengeluaran)
        Route::get('api/summary-expenses/data', [SummaryExpenseController::class, 'getAllData']);
        Route::resource('summary-expenses', SummaryExpenseController::class)->except(['edit', 'update']);

        // data tailor (Data Penjahit)
        Route::get('api/data-tailors/data', [DataTailorController::class, 'getAllData']);
        Route::resource('data-tailors', DataTailorController::class)->except(['edit', 'update']);
    });

    // settings
    Route::prefix('settings')->group(function () {
        // list accounting estimates (DP akuntasi)
        Route::resource('list-accounting-estimates', ListAccountingEstimateController::class);

        // list supplier estimates (DP supplier)
        Route::resource('list-supplier-estimates', ListSupplierEstimateController::class);

        // list color estimates (DP warna)
        Route::resource('list-color-estimates', ListColorEstimateController::class);

        // list unit measure estimates (DP satuan ukur)
        Route::resource('list-unit-measure-estimates', ListUnitMeasureEstimateController::class);

        // lvl 1 type materials (DP jenis bahan lvl 1)
        Route::resource('lvl1-type-materials', Lvl1TypeMaterialController::class);

        // lvl 2 type materials (DP jenis bahan lvl 2)
        Route::resource('lvl2-type-materials', Lvl2TypeMaterialController::class);

        // lvl 3 type materials (DP jenis bahan lvl 3)
        Route::resource('lvl3-type-materials', Lvl3TypeMaterialController::class);

        // unit internals (Satuan internal)
        Route::resource('unit-internals', UnitInternalController::class);

        // unit suppliers (Satuan supplier)
        Route::resource('unit-suppliers', UnitSupplierController::class);

        // wages estimates (DP upah)
        Route::resource('wages-estimates', WagesEstimateController::class);

        // worksheet abbreviations (DP singkatan worksheet)
        Route::resource('worksheet-abbreviations', WorksheetAbbreviationController::class);

        // data warehouses (DP gudang)
        Route::resource('data-warehouses', DataWarehouseController::class);

        // category expenses (DP kategori pengeluaran)
        Route::resource('category-expenses', CategoryExpenseController::class);

        // coordinator codes (DP kode koordinator)
        Route::resource('coordinator-codes', CoordinatorCodeController::class);

        // area codes (DP kode daerah)
        Route::resource('area-codes', AreaCodeController::class);

        // tailor codes (DP kode penjahit)
        Route::get('api/tailor-codes/data', [TailorCodeController::class, 'getAllData']);
        Route::resource('tailor-codes', TailorCodeController::class);
    });

});

// profile edit, update, destroy
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// panggil file auth.php
require __DIR__ . '/auth.php';

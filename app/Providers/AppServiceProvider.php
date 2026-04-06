<?php

namespace App\Providers;

use App\Models\CoordinatorCode;
use App\Models\Lvl1TypeMaterial;
use App\Models\StockOpname;
use App\Models\DatabaseMaterial;
use App\Observers\DatabaseMaterialObserver;
use App\Observers\Lvl1TypeMaterialObserver;
use App\Models\Lvl2TypeMaterial;
use App\Observers\Lvl2TypeMaterialObserver;
use App\Models\Lvl3TypeMaterial;
use App\Observers\Lvl3TypeMaterialObserver;
use App\Models\ListColorEstimate;
use App\Observers\ListColorEstimateObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\ListSupplierEstimate;
use App\Observers\ListSupplierEstimateObserver;
use App\Models\IncomingRawMaterial;
use App\Models\ListAccountingEstimate;
use App\Observers\ListAccountingEstimateObserver;
use App\Observers\IncomingRawMaterialObserver;
use App\Models\IncomingComplementMaterial;
use App\Observers\IncomingComplementMaterialObserver;
use App\Models\UnitInternal;
use App\Observers\UnitInternalObserver;
use App\Models\DataWarehouse;
use App\Observers\DataWarehouseObserver;
use App\Observers\StockOpnameObserver;
use App\Observers\DataExpenseObserver;
use App\Models\DataExpense;
use App\Observers\CoordinatorCodeObserver;
use App\Models\AreaCode;
use App\Models\TailorCode;
use App\Observers\AreaCodeObserver;
use App\Observers\TailorCodeObserver;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrasi Observer untuk ListSupplierEstimate
        ListSupplierEstimate::observe(ListSupplierEstimateObserver::class);

        IncomingRawMaterial::observe(IncomingRawMaterialObserver::class);
        IncomingComplementMaterial::observe(IncomingComplementMaterialObserver::class);

        ListAccountingEstimate::observe(ListAccountingEstimateObserver::class);
        Lvl1TypeMaterial::observe(Lvl1TypeMaterialObserver::class);
        Lvl2TypeMaterial::observe(Lvl2TypeMaterialObserver::class);
        Lvl3TypeMaterial::observe(Lvl3TypeMaterialObserver::class);
        ListColorEstimate::observe(ListColorEstimateObserver::class);

        DatabaseMaterial::observe(DatabaseMaterialObserver::class);
        UnitInternal::observe(UnitInternalObserver::class);

        DataWarehouse::observe(DataWarehouseObserver::class);

        StockOpname::observe(StockOpnameObserver::class);

        DataExpense::observe(DataExpenseObserver::class);

        CoordinatorCode::observe(CoordinatorCodeObserver::class);

        AreaCode::observe(AreaCodeObserver::class);

        TailorCode::observe(TailorCodeObserver::class);

        // testing ngrok
        // if (config('app.env') === 'local') {
        //     URL::forceScheme('https');
        // }
    }
}

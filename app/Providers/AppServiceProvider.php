<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Choir;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\Category;
use App\Models\Composer;
use App\Models\Collection;
use App\Models\Partitur;
use App\Models\Merchandise;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (Auth::guard('customer')->check()) {
                $idCustomer = Auth::guard('customer')->id();

                $choirs = Choir::where('customer_id', $idCustomer)->get();
                $choirCount = $choirs->count();
                
                if($choirCount == 0){
                    $choirs = '';
                }
            }else{
                $choirs = '';
                $choirCount = 0;
            }
            $countries = Country::orderBy('country_name')->get();
            $footer_category = Category::with(['details' => function($query) {
                $query->orderBy('name');
            }])->where('type', 'Sheet Music')->orderBy('name')->limit(3)->get();
            
            View::share('choirCount', $choirCount);
            View::share('choir', $choirs);
            View::share('countries', $countries);
            View::share('footer_category', $footer_category);


            // search bar
            $composer = Composer::orderBy('name')->get();
            View::share('composerSearch', $composer);

            $collection = Collection::orderBy('name')->get();
            View::share('collectionSearch', $collection);

            $partitur = Partitur::orderBy('name')->get();
            View::share('partiturSearch', $partitur);

            $merchandise = Merchandise::orderBy('name')->get();
            View::share('merchandiseSearch', $merchandise);
        });
    }
}

<?php

use Illuminate\Support\Facades\Route;

// Web Utama
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComposerController as CO;
use App\Http\Controllers\CollectionController as CL;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;

// Admin
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CollectionController;
use App\Http\Controllers\admin\ComposerController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\PartiturController;
use App\Http\Controllers\admin\MerchandiseController;
use App\Http\Controllers\admin\VoucherController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\UserController;

// import
use App\Imports\ComposersImport;
use App\Imports\CollectionImport;
use App\Imports\MerchandiseImport;


// for import excel
use Maatwebsite\Excel\Facades\Excel;

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

// Homepage
Route::get('/', [HomepageController::class, 'index'])->name('homepage');

// Composer
Route::get('/composer', [CO::class, 'index'])->name('composer');
Route::get('/composer/{name}', [CO::class, 'show'])->name('composer-detail');

// Collection
Route::get('/collection', [CL::class, 'index'])->name('collection');
Route::get('/collection/sort', [CL::class, 'indexSort'])->name('collection-sort-blank');
Route::get('/collection/sort/{sort}', [CL::class, 'indexSort'])->name('collection-sort');
Route::get('/collection/{name}', [CL::class, 'show'])->name('collection-detail');

// Publisher
Route::get('/publisher', [PublisherController::class, 'index'])->name('publisher');
Route::get('/publisher/{name}', [PublisherController::class, 'show'])->name('publisher.detail');
// create for mechandise detail
Route::get('/merchandise/{name}', [PublisherController::class, 'showMerchandise'])->name('merchandise.detail');

// Checkout
Route::get('/payment/notification', [CheckoutController::class, 'notification'])->name('payment.notification');

// buat route checkout.pay
Route::post('/checkout/pay', [CheckoutController::class, 'createPayment'])->name('checkout.pay');

Route::post('/add-to-cart', [CheckoutController::class, 'addToCart'])->name('add.to.cart');

Route::get('/download-pdf/{id}',  [CheckoutController::class, 'downloadPDF'])->name('download-pdf');

// fetch Penerbit
Route::get('/fetch-partitur', [PublisherController::class, 'fetchPartitur'])->name('fetch.partitur');
// fetch Merchandise
Route::get('/fetch-merchandise', [PublisherController::class, 'fetchMerchandise'])->name('fetch.merchandise');
Route::get('/filters', [PublisherController::class, 'getFilters'])->name('get.filters');

// Customer Auth
Route::middleware(['guest:customer'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticateLogin'])->name('customer.login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeregister'])->name('customer.register');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/send-forgot-password', [AuthController::class, 'sendForgotPassword'])->name('send-forgot-password');
    Route::get('/reset-password/{token?}', [AuthController::class, 'resetPassword'])->name('reset-password');
    Route::post('/save-reset-password', [AuthController::class, 'saveResetPassword'])->name('save-reset-password');
});

// Customer Menu
Route::middleware(['auth:customer'])->group(function () {
    // Cart
    Route::get('/cart-data', [CheckoutController::class, 'getCartData'])->name('cart.data');
    Route::post('/cart/update-competition', [CheckoutController::class, 'updateCompetitionStatus'])->name('cart.updateCompetition');
    Route::post('/cart/update-choir', [CheckoutController::class, 'updateChoirStatus'])->name('cart.updateChoir');
    Route::get('/get-address-by-id/{id}', [CheckoutController::class, 'getAddressById'])->name('get.address.by.id');
    Route::post('/cart/delete/{id}', [CheckoutController::class, 'delete'])->name('cart.delete');
    Route::post('/cart/update', [CheckoutController::class, 'update'])->name('cart.update');
    Route::post('/cart/updateDetail', [CheckoutController::class, 'updateDetail'])->name('cart.updateDetail');

    // checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/{id}', [CheckoutController::class, 'checkoutCustom'])->name('checkout-custom');
    Route::post('/check-voucher', [CheckoutController::class, 'voucher'])->name('check-voucher');
    Route::get('/change-shipping', [CheckoutController::class, 'changeShipping'])->name('change-shipping');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/save', [ProfileController::class, 'profileSave'])->name('save-profile');
    Route::post('/profile/save/photo', [ProfileController::class, 'photoave'])->name('save-photo');
    Route::get('/provinces/{country_id}', [ProfileController::class, 'getProvincesByCountry'])->name('getProvincesByCountry');
    Route::get('/cities/{provinces_id}', [ProfileController::class, 'getCitiesByProvinces'])->name('getCitiesByProvinces');

    // Choir
    Route::get('/choir', [ProfileController::class, 'choir'])->name('choir');
    Route::post('/choir/save', [ProfileController::class, 'choirSave'])->name('save-choir');
    Route::put('/choir/change/{id}', [ProfileController::class, 'choirChange'])->name('change-choir');
    Route::post('/choir/update', [ProfileController::class, 'choirUpdate'])->name('update-choir');
    Route::delete('/choir/change/{id}', [ProfileController::class, 'choirDelete'])->name('delete-choir');

    // order
    Route::get('/order/{id}', [ProfileController::class, 'order'])->name('order');
    Route::get('/invoice/{id}', [ProfileController::class, 'invoice'])->name('invoice');

    // Address
    Route::get('/address', [ProfileController::class, 'address'])->name('address');
    Route::post('/shipment/save', [ProfileController::class, 'shipmentSave'])->name('save-shipment');
    Route::put('/shipment/change/{id}', [ProfileController::class, 'shipmentChange'])->name('change-shipment');
    Route::post('/shipment/update', [ProfileController::class, 'shipmentUpdate'])->name('update-shipment');
    Route::delete('/shipment/change/{id}', [ProfileController::class, 'shipmentDelete'])->name('delete-shipment');

    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


// Admin
Route::prefix('admin')->group(function () {
    // Admin Auth
    Route::middleware(['guest:admin'])->group(function () {
        Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
        Route::post('/auth', [AdminController::class, 'authenticate'])->name('admin.auth');
    });

    // Admin Menu
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Banner
        Route::resource('banner', BannerController::class);

        // Category
        Route::resource('category', CategoryController::class);
        Route::post('/category/import', [CategoryController::class, 'import'])->name('category.import');

        // Composer
        Route::resource('composer', ComposerController::class);
        Route::post('/composer/import', function () {
            if (request()->hasFile('excel')) {
                $path = request()->file('excel')->store('temp');
                $realPath = Storage::path($path);
                Excel::import(new ComposersImport, $realPath, null, \Maatwebsite\Excel\Excel::XLSX);
                Storage::delete($path); // Hapus file setelah import jika diinginkan
                return back()->with('success', 'All good!');
            } else {
                return back()->withError('No file was uploaded.');
            }
        })->name('composer.import');

        // Collection
        Route::resource('collection', CollectionController::class);
        Route::post('/collection/import', function () {
            if (request()->hasFile('excel')) {
                $path = request()->file('excel')->store('temp');
                $realPath = Storage::path($path);
                Excel::import(new CollectionImport, $realPath, null, \Maatwebsite\Excel\Excel::XLSX);
                Storage::delete($path); // Hapus file setelah import jika diinginkan
                return back()->with('success', 'All good!');
            } else {
                return back()->withError('No file was uploaded.');
            }
        })->name('collection.import');

        // Customer
        Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/customer/{customer}', [CustomerController::class, 'show']);
        Route::get('/customer/{customer}/alamat', [CustomerController::class, 'alamat'])->name('customer.alamat');
        Route::get('/customer/{customer}/choir', [CustomerController::class, 'choir'])->name('customer.choir');

        // Partitur
        Route::resource('sheet-music', PartiturController::class);
        Route::post('/sheet-music/import', [PartiturController::class, 'import'])->name('sheet-music.import');

        // Merchandise
        Route::resource('merchandise', MerchandiseController::class);
        Route::post('/merchandise/import', [MerchandiseController::class, 'import'])->name('merchandise.import');

        // Voucher
        Route::resource('voucher', VoucherController::class);

        // Order
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.detail');
        Route::post('/order/save-receipt-number', [OrderController::class, 'saveReceiptNumber'])->name('order.saveReceiptNumber');
        Route::post('/order/save-complete', [OrderController::class, 'saveComplete'])->name('order.saveComplete');

        // Role
        Route::resource('role', RoleController::class);

        // User
        Route::resource('user', UserController::class);

        // Logout
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});
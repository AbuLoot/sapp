<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Joystick\AdminController;
use App\Http\Controllers\Joystick\PageController;
use App\Http\Controllers\Joystick\PostController;
use App\Http\Controllers\Joystick\SectionController;
use App\Http\Controllers\Joystick\CategoryController;
use App\Http\Controllers\Joystick\ProductController;
use App\Http\Controllers\Joystick\ProductExtensionController;
use App\Http\Controllers\Joystick\BannerController;
use App\Http\Controllers\Joystick\AppController;
use App\Http\Controllers\Joystick\OrderController;
use App\Http\Controllers\Joystick\OptionController;
use App\Http\Controllers\Joystick\ModeController;
use App\Http\Controllers\Joystick\CompanyController;
use App\Http\Controllers\Joystick\ProjectController;
use App\Http\Controllers\Joystick\RegionController;
use App\Http\Controllers\Joystick\CurrencyController;
use App\Http\Controllers\Joystick\UnitController;
use App\Http\Controllers\Joystick\UserController;
use App\Http\Controllers\Joystick\RoleController;
use App\Http\Controllers\Joystick\PermissionController;
use App\Http\Controllers\Joystick\LanguageController;

// Sanapp Admin Controllers
use App\Http\Controllers\Joystick\OfficeController;
use App\Http\Controllers\Joystick\StoreController;
use App\Http\Controllers\Joystick\CashbookController;
use App\Http\Controllers\Joystick\WorkplaceController;
use App\Http\Controllers\Joystick\BankAccountController;
use App\Http\Controllers\Joystick\PaymentTypeController;
use App\Http\Controllers\Joystick\DocTypeController;
use App\Http\Controllers\Joystick\DiscountController;

// Store
use App\Http\Controllers\Store\StoreIndexController;
use App\Http\Controllers\Store\DocController;
use App\Http\Controllers\Store\RevisionController;
use App\Http\Controllers\Store\RevisionProductController;
use App\Http\Controllers\Store\WriteoffController;
use App\Http\Controllers\Store\IncomingDocController;
use App\Http\Controllers\Store\OutgoingDocController;

// Cashbook
use App\Http\Controllers\Cashbook\CashbookIndexController;
use App\Http\Controllers\Cashbook\CashDocController;
use App\Http\Controllers\Cashbook\IncomingOrderController;
use App\Http\Controllers\Cashbook\IncomingCheckController;
use App\Http\Controllers\Cashbook\OutgoingOrderController;
use App\Http\Controllers\Cashbook\CashShiftJournalController;

// Site Controllers
use App\Http\Controllers\InputController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\PostController as NewsController;
use App\Http\Controllers\PageController as SiteController;

Route::get('bc', function(){

$time_start = microtime(true);

// Спим некоторое время
usleep(100);

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Ничего не делал $time секунд\n<br>";
echo substr(intval(microtime(true)), 7);
});

// Sanapp Store
Route::redirect('/store', '/'.app()->getLocale().'/store');

Route::group(['prefix' => '{lang}/store', 'middleware' => ['auth' , 'roles:admin|storekeeper']], function () {

    Route::get('/', [StoreIndexController::class, 'index']);
    Route::get('/income', [StoreIndexController::class, 'income']);

    Route::get('add-product', [IncomingDocController::class, 'create']);
    Route::get('edit-product', [IncomingDocController::class, 'edit']);
    // Route::post('add-product', [IncomingDocController::class, 'store']);
    Route::get('docs/outgoing', [OutgoingDocController::class, 'index']);

    Route::resources([
        // 'store' => StoreController::class,
        'docs' => IncomingDocController::class,
        'outgoing_docs' => OutgoingDocController::class,
        'revision' => RevisionController::class,
        'writeoff' => WriteoffController::class,
        'companies' => CompanyController::class,
        'categories' => CategoryController::class,
        'products' => ProductController::class,
    ]);
});

// Sanapp Cashbook
// Route::redirect('/cashbook', '/'.app()->getLocale().'/cashbook');

Route::group(['prefix' => '{lang}/cashbook', 'middleware' => ['auth' , 'roles:admin|cashier']], function () {

    Route::get('/', [CashbookIndexController::class, 'index']);

    Route::resources([
        // 'cashbook' => CashbookIndexController::class,
        'cash_docs' => CashDocController::class,
        'cash_shift_journal' => CashShiftJournalController::class,
        'incoming_orders' => IncomingOrderController::class,
        'incoming_checks' => IncomingCheckController::class,
        'outgoing_orders' => OutgoingOrderController::class,
    ]);
});

// Sanapp Joystick Administration
// Route::redirect('/admin', '/'.app()->getLocale().'/admin');

Route::group(['prefix' => '{lang}/admin', 'middleware' => ['auth' , 'roles:admin|storekeeper|cashier|manager']], function () {

    Route::get('/', [AdminController::class, 'index']);
    Route::get('filemanager', [AdminController::class, 'filemanager']);
    Route::get('frame-filemanager', [AdminController::class, 'frameFilemanager']);

    Route::resources([

        // Sanapp routes
        'office' => OfficeController::class,
        'stores' => StoreController::class,
        'cashbooks' => CashbookController::class,
        'workplaces' => WorkplaceController::class,
        'bank_accounts' => BankAccountController::class,
        'payment_types' => PaymentTypeController::class,
        'doc_types' => DocTypeController::class,
        'units' => UnitController::class,
        'discounts' => DiscountController::class,

        'pages' => PageController::class,
        'posts' => PostController::class,
        'sections' => SectionController::class,
        'categories' => CategoryController::class,
        'projects' => ProjectController::class,
        'products' => ProductController::class,
        'banners' => BannerController::class,
        'apps' => AppController::class,
        'orders' => OrderController::class,
        'options' => OptionController::class,
        'modes' => ModeController::class,
        'companies' => CompanyController::class,
        'currencies' => CurrencyController::class,
        'regions' => RegionController::class,
        'users' => UserController::class,
        'roles' => RoleController::class,
        'permissions' => PermissionController::class,
        'languages' => LanguageController::class,
    ]);

    Route::get('categories-actions', [CategoryController::class, 'actionCategories']);
    Route::get('companies-actions', [CompanyController::class, 'actionCompanies']);
    Route::get('projects-actions', [ProjectController::class, 'actionProjects']);

    Route::get('products/{id}/comments', [ProductController::class, 'comments']);
    Route::get('products/{id}/destroy-comment', [ProductController::class, 'destroyComment']);

    Route::get('products-search', [ProductExtensionController::class, 'search']);
    Route::get('products-search-ajax', [ProductExtensionController::class, 'searchAjax']);
    Route::get('products-actions', [ProductExtensionController::class, 'actionProducts']);
    Route::get('products-category/{id}', [ProductExtensionController::class, 'categoryProducts']);
    Route::get('joytable', [ProductExtensionController::class, 'joytable']);
    Route::post('joytable-update', [ProductExtensionController::class, 'joytableUpdate']);
    Route::get('products-export', [ProductExtensionController::class, 'export']);
    Route::get('products-import', [ProductExtensionController::class, 'importView']);
    Route::post('products-import', [ProductExtensionController::class, 'import']);
    Route::get('products-price/edit', [ProductExtensionController::class, 'calcForm']);
    Route::post('products-price/update', [ProductExtensionController::class, 'priceUpdate']);

    Route::get('users/password/{id}/edit', [UserController::class, 'passwordEdit']);
    Route::put('users/password/{id}', [UserController::class, 'passwordUpdate']);
});


// Input Actions
Route::get('search', [InputController::class, 'search']);
Route::get('search-ajax', [InputController::class, 'searchAjax']);
Route::get('search-ajax-admin', [InputController::class, 'searchAjaxAdmin']);
Route::post('filter-products', [InputController::class, 'filterProducts']);
Route::post('send-app', [InputController::class, 'sendApp']);


// Shop
Route::get('/', [ShopController::class, 'index']);

// Route::get('brand/{company}', [ShopController::class, 'brandProducts']);
// Route::get('brand/{company}/{project}/{id}', [ShopController::class, 'brandCategoryProducts']);

Route::get('b/{project}/{id}', [ShopController::class, 'projectProducts']);
Route::get('b/{project}/{subproject}/{id}', [ShopController::class, 'subProjectProducts']);

Route::get('c/{category}/{id}', [ShopController::class, 'categoryProducts']);
Route::get('c/{category}/{subcategory}/{id}', [ShopController::class, 'subCategoryProducts']);

Route::get('p/{id}-{product}', [ShopController::class, 'product']);
Route::post('comment-product', [ShopController::class, 'saveComment']);


// Cart Actions
Route::get('cart', [CartController::class, 'cart']);
Route::get('checkout', [CartController::class, 'checkout']);
Route::get('add-to-cart/{id}', [CartController::class, 'addToCart']);
Route::get('remove-from-cart/{id}', [CartController::class, 'removeFromCart']);
Route::get('clear-cart', [CartController::class, 'clearCart']);
Route::post('store-order', [CartController::class, 'storeOrder']);
Route::get('destroy-from-cart/{id}', [CartController::class, 'destroy']);


// Favourite Actions
Route::get('favorite', [FavouriteController::class, 'getFavorite']);
Route::get('toggle-favourite/{id}', [FavouriteController::class, 'toggleFavourite']);

// User Profile
Route::group(['middleware' => 'auth'], function() {

    Route::get('profile', [ProfileController::class, 'profile']);
    Route::get('profile/edit', [ProfileController::class, 'editProfile']);
    Route::put('profile', [ProfileController::class, 'updateProfile']);
    Route::get('orders', [ProfileController::class, 'myOrders']);
});

// News
Route::get('news', [NewsController::class, 'posts']);
Route::get('i/news-category', [NewsController::class, 'postsCategory']);
Route::get('news/{page}', [NewsController::class, 'postSingle']);
Route::post('comment-news', [NewsController::class, 'saveComment']);

// Pages
Route::get('i/catalogs', [SiteController::class, 'catalogs']);
Route::get('i/contacts', [SiteController::class, 'contacts']);
Route::get('i/{page}', [SiteController::class, 'page']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

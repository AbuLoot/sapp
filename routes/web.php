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
use App\Http\Controllers\Joystick\UserController;
use App\Http\Controllers\Joystick\RoleController;
use App\Http\Controllers\Joystick\PermissionController;
use App\Http\Controllers\Joystick\LanguageController;

// Sanapp Admin Controllers
use App\Http\Controllers\POS\OfficeController;
use App\Http\Controllers\POS\CashDocController;
use App\Http\Controllers\POS\StoreController;
use App\Http\Controllers\POS\CashbookController;
use App\Http\Controllers\POS\WorkplaceController;
use App\Http\Controllers\POS\BankAccountController;
use App\Http\Controllers\POS\PaymentTypeController;
use App\Http\Controllers\POS\DocTypeController;
use App\Http\Controllers\POS\DiscountController;
use App\Http\Controllers\POS\UnitController;

// Sanapp Report Controllers
use App\Http\Controllers\POS\Reports\FinancialReportController;
use App\Http\Controllers\POS\Reports\CustomersReportController;
use App\Http\Controllers\POS\Reports\ContractorsReportController;
use App\Http\Controllers\POS\Reports\WorkersReportController;
use App\Http\Controllers\POS\Reports\StoresReportController;
use App\Http\Controllers\POS\Reports\CashReconciliationController;

// Site Controllers
use App\Http\Controllers\InputController;
// use App\Http\Controllers\ShopController;
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\CartController;
// use App\Http\Controllers\FavouriteController;
// use App\Http\Controllers\PostController as NewsController;
use App\Http\Controllers\PageController as SiteController;

// Storage
use App\Http\Livewire\Store\Index as StoreIndex;
use App\Http\Livewire\Store\AddProduct;
use App\Http\Livewire\Store\EditProduct;
use App\Http\Livewire\Store\Income;
use App\Http\Livewire\Store\IncomeDraft;
use App\Http\Livewire\Store\IncomingDocs;
use App\Http\Livewire\Store\OutgoingDocs;
use App\Http\Livewire\Store\Inventory;
use App\Http\Livewire\Store\InventoryDraft;
use App\Http\Livewire\Store\InventoryHistory;
use App\Http\Livewire\Store\InventoryDetail;
use App\Http\Livewire\Store\Writeoff;
use App\Http\Livewire\Store\StoreDocs;
use App\Http\Livewire\Store\StoreDocsPrint;
use App\Http\Livewire\Store\PriceTags;

// Cashdesk
use App\Http\Livewire\Cashbook\Index as CashbookIndex;
use App\Http\Livewire\Cashbook\PaymentTypes\PaymentTypesIndex;
use App\Http\Livewire\Cashbook\PaymentTypes\CashPayment;
use App\Http\Livewire\Cashbook\PaymentTypes\BankCard;
use App\Http\Livewire\Cashbook\PaymentTypes\ComplexPayment;
use App\Http\Livewire\Cashbook\PaymentTypes\SaleOnCredit;
use App\Http\Livewire\Cashbook\PaymentTypes\KaspiTransfer;
use App\Http\Livewire\Cashbook\PaymentTypes\Success;
use App\Http\Livewire\Cashbook\CashDocsPrint;

use App\Http\Controllers\Auth\StorageVerificationController;
use App\Http\Controllers\Auth\CashdeskVerificationController;

// Sanapp Storage
Route::redirect('/storage', '/'.app()->getLocale().'/storage');

Route::group(['prefix' => '{lang}/storage', 'middleware' => ['auth' , 'roles:admin|storekeeper', 'verify.storage']], function () {

    // Custom Auth Code
    Route::get('verification', [StorageVerificationController::class, 'create'])->withoutMiddleware('verify.storage');
    Route::post('verification', [StorageVerificationController::class, 'store'])->withoutMiddleware('verify.storage');

    // Livewire Routes
    Route::get('/', StoreIndex::class);
    Route::get('docsprint/{type}/{id}', StoreDocsPrint::class);
    Route::get('pricetags/{ids}', PriceTags::class);
    Route::get('add-product', AddProduct::class);
    Route::get('edit-product/{id}', EditProduct::class);
    Route::get('income', Income::class);
    Route::get('income/drafts', IncomeDraft::class);
    Route::get('docs', IncomingDocs::class);
    Route::get('docs/outgoing', OutgoingDocs::class);
    Route::get('inventory', Inventory::class);
    Route::get('inventory/drafts', InventoryDraft::class);
    Route::get('inventory-history', InventoryHistory::class);
    Route::get('inventory-detail/{id}', InventoryDetail::class);
    Route::get('writeoff', Writeoff::class);
    Route::get('storedocs', StoreDocs::class);
});


// Sanapp Cashdesk
Route::redirect('/cashdesk', '/'.app()->getLocale().'/cashdesk');

Route::group(['prefix' => '{lang}/cashdesk', 'middleware' => ['auth' , 'roles:admin|cashier', 'verify.cashdesk']], function () {

    // Custom Auth Code
    Route::get('verification', [CashdeskVerificationController::class, 'create'])->withoutMiddleware('verify.cashdesk');
    Route::post('verification', [CashdeskVerificationController::class, 'store'])->withoutMiddleware('verify.cashdesk');

    // Livewire Routes
    Route::get('/', CashbookIndex::class);
    Route::get('docsprint/{type}/{id}', CashDocsPrint::class);
    Route::get('payment-types', PaymentTypesIndex::class);
    Route::get('payment-type/cash-payment', CashPayment::class);
    Route::get('payment-type/bank-card', BankCard::class);
    Route::get('payment-type/complex-payment', ComplexPayment::class);
    Route::get('payment-type/sale-on-credit', SaleOnCredit::class);
    Route::get('payment-type/kaspi-transfer', KaspiTransfer::class);
    Route::get('payment-type/success', Success::class);
});


// Sanapp POS Administration
Route::redirect('/pos', '/'.app()->getLocale().'/pos');

Route::group(['prefix' => '{lang}/pos', 'middleware' => ['auth' , 'roles:admin|manager']], function () {

    Route::get('/', [OfficeController::class, 'index']);

    Route::resources([
        'cashdocs' => CashDocController::class,
        'stores' => StoreController::class,
        'cashbooks' => CashbookController::class,
        'workplaces' => WorkplaceController::class,
        'bank_accounts' => BankAccountController::class,
        'payment_types' => PaymentTypeController::class,
        'doc_types' => DocTypeController::class,
        'units' => UnitController::class,
        'discounts' => DiscountController::class,
    ]);

    // Reports
    Route::get('report-financial', [FinancialReportController::class, 'index']);
    Route::get('report-customers', [CustomersReportController::class, 'index']);
    Route::get('report-contractors', [ContractorsReportController::class, 'index']);
    Route::get('report-workers', [WorkersReportController::class, 'index']);
    Route::get('report-stores', [StoresReportController::class, 'index']);
    Route::get('cash-reconciliation', [CashReconciliationController::class, 'index']);
});


// Joystick Administration
Route::redirect('/admin', '/'.app()->getLocale().'/admin');

Route::group(['prefix' => '{lang}/admin', 'middleware' => ['auth' , 'roles:admin|manager']], function () {

    Route::get('/', [AdminController::class, 'index']);
    Route::get('filemanager', [AdminController::class, 'filemanager']);
    Route::get('frame-filemanager', [AdminController::class, 'frameFilemanager']);

    Route::resources([
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


// Route::get('b/{project}/{id}', [ShopController::class, 'projectProducts']);
// Route::get('b/{project}/{subproject}/{id}', [ShopController::class, 'subProjectProducts']);
// Route::get('c/{category}/{id}', [ShopController::class, 'categoryProducts']);
// Route::get('c/{category}/{subcategory}/{id}', [ShopController::class, 'subCategoryProducts']);
// Route::get('p/{id}-{product}', [ShopController::class, 'product']);
// Route::post('comment-product', [ShopController::class, 'saveComment']);


// Cart Actions
// Route::get('cart', [CartController::class, 'cart']);
// Route::get('checkout', [CartController::class, 'checkout']);
// Route::get('add-to-cart/{id}', [CartController::class, 'addToCart']);
// Route::get('remove-from-cart/{id}', [CartController::class, 'removeFromCart']);
// Route::get('clear-cart', [CartController::class, 'clearCart']);
// Route::post('store-order', [CartController::class, 'storeOrder']);
// Route::get('destroy-from-cart/{id}', [CartController::class, 'destroy']);


// Favourite Actions
// Route::get('favorite', [FavouriteController::class, 'getFavorite']);
// Route::get('toggle-favourite/{id}', [FavouriteController::class, 'toggleFavourite']);

// User Profile
// Route::group(['middleware' => 'auth'], function() {

//     Route::get('profile', [ProfileController::class, 'profile']);
//     Route::get('profile/edit', [ProfileController::class, 'editProfile']);
//     Route::put('profile', [ProfileController::class, 'updateProfile']);
//     Route::get('orders', [ProfileController::class, 'myOrders']);
// });

// News
// Route::get('news', [NewsController::class, 'posts']);
// Route::get('i/news-category', [NewsController::class, 'postsCategory']);
// Route::get('news/{page}', [NewsController::class, 'postSingle']);
// Route::post('comment-news', [NewsController::class, 'saveComment']);

// Pages
Route::get('i/contacts', [SiteController::class, 'contacts']);
Route::get('i/{page}', [SiteController::class, 'page']);

Route::get('/apps', function () {
    return view('apps');
})->middleware(['auth'])->name('apps');

require __DIR__.'/auth.php';

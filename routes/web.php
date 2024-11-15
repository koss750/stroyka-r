<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\DailyAverageRateController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UIController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceModuleController;
use App\Http\Controllers\FulfillmentController;
use App\Http\Controllers\SectionItemController;
use App\Http\Controllers\ExternalSimulationController;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\OrderController;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TurboPageController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Cache;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/site');
});

Route::get('/home', function () {
    return redirect('/site');
});

Route::get('/clear-cache', function () {
    Cache::flush();
    return redirect('/site');
});

Route::get('/tinkoff-payment-iframe/{urlcontent}', function ($urlcontent) {
    return base64_decode($urlcontent);
});

//under construction - переделать
Route::prefix('projects')->middleware('check.redirection')->group(function () {
    Route::view('/{slug}', 'under-construction')->middleware('counter');
});

Route::get('/forex', [ExchangeRateController::class, 'index']);
Route::get('/export', [DesignController::class, 'exportAll']);
Route::get('/forex-day', [DailyAverageRateController::class, 'index']);
Route::get('/browse/{category?}', [DesignController::class, 'getDemoDesigns']);
Route::get('/project/{id}', [DesignController::class, 'getDemoDetail'])->middleware('counter')->name('design.show');
Route::get('/turbo-pages-rss', [TurboPageController::class, 'generateRssFeed'])->name('turbo-pages.rss');
Route::get('/turbo-pages', [TurboPageController::class, 'indexAllProjects'])->name('turbo-pages.index');
Route::get('/checkout', [DesignController::class, 'getDemoCheckout']);
Route::get('/email-inbox', [UIController::class, 'email_inbox']);
//Route::get('/messages', [UIController::class, 'email_inbox']);
Route::get('/email-compose', [UIController::class, 'email_compose']);
Route::get('/email-read', [UIController::class, 'email_read']);
Route::get('/page-login', [UIController::class, 'page_login']);
Route::get('/app-profile', [UIController::class, 'app_profile']);
Route::get('/suppliers', [SupplierController::class, 'indexSuppliers'])->name('suppliers.index');
Route::get('/dashboard', [UIController::class, 'dashboard_1']);
Route::get('/register', [UIController::class, 'page_register']);
Route::get('/str', [UIController::class, 'str']);
Route::post('/smeta', [InvoiceController::class, 'invoiceViewReferences']);
Route::get('/trigger-invoice-view', [InvoiceController::class, 'triggerInvoiceView']);
Route::get('/smeta/{id}', [InvoiceController::class, 'invoiceViewFull']);
Route::get('/register-supplier', [UIController::class, 'page_register_supplier']);
Route::get('/register-contractor', [UIController::class, 'page_register_contractor']);
Route::get('/chats/{conversation}', [ChatController::class, 'show'])->name('chats.show');
Route::get('/skachatushki', function () {
    return InvoiceModuleController::downloadInvoiceItemsCsv();
});
Route::prefix('invoices')->group(function () {
    Route::post('/process-multiple', [InvoiceModuleController::class, 'processMultiple']);
    Route::get('/{invoice}/sections', [InvoiceModuleController::class, 'gatherSection']);
    Route::post('/sections/{section}', [InvoiceModuleController::class, 'fillSections']);
    Route::get('/{invoice}/variables', [InvoiceModuleController::class, 'getVariables']);
    Route::post('/final-calculation', [InvoiceModuleController::class, 'finalCalculation']);
});
Route::prefix('fundament')->group(function () {
    Route::get('/fundament-lentochnyj', [UIController::class, 'lentaFoundationCalculator']);
    Route::get('/fundament-svayno-rostverkovyy-s-plitoy', [UIController::class, 'SRPFoundationCalculator']);
    Route::get('/fundament-svayno-rostverkovyy', [UIController::class, 'SRFoundationCalculator']);
    Route::get('/fundament-lentochniy-s-plitoy', [UIController::class, 'LPFoundationCalculator']);
    Route::get('/fundament-monolitnaya-plita', [UIController::class, 'MPFoundationCalculator']);
    Route::view('/{slug}', 'under-construction')->middleware('counter');
});
/*
Route::middleware(['check.redirection'])->group(function () {
    Route::get('/projects/{slug}', [UIController::class, 'showProjects'])->middleware('counter');
});
*/
//Route::get('/metal', [SectionItemController::class, 'addMetal']);
//Route::get('/external', [ExternalSimulationController::class, 'process']);
Route::get('/simulate', [App\Http\Controllers\BetSimulationController::class, 'simulate']);
Route::prefix('site')->group(function () {
    Route::get('/', function () {
        return view('index');
    //    $foundations = DynamicPageCard::where('type', 'foundation')->get()->toArray();
    //    $cards = DynamicPageCard::where('type', 'home')->get()->toArray();

//        return view('index', compact('cards', 'foundations'));
    });
});

//Routes related to project execution -- для выполнения проектов исполнителями
Route::post('/projects/{project}/assign-executors', [ProjectController::class, 'assignExecutors']);
Route::get('/projects/{project}/review', [ProjectController::class, 'reviewProject']);
Route::post('/projects/{project}/submit-offer', [ProjectController::class, 'submitOffer']);
Route::get('/projects/{project}/available-executors', [ProjectController::class, 'getAvailableExecutors']);
Route::post('/projects/{project}/assign-executor', [ProjectController::class, 'assignExecutor']);

Route::get('/keys/{designId}', function ($designId) {
    $keys = Redis::connection('external')->keys("*");
    return $keys;
});

Route::post('/register-order-smeta', [ProjectController::class, 'createSmetaOrder']);

Route::get('/search-designs', [DesignController::class, 'search']);

Route::prefix('vora')->group(function () {
    Route::controller(UIController::class)->group(function() {
        Route::get('/','dashboard_1');
        Route::get('/index','dashboard_1');
        Route::get('/index-2','dashboard_2');
        //Route::get('/projects','projects');
        Route::get('/contacts','contacts');
        Route::get('/kanban','kanban');
        Route::get('/calendar','calendar');
        Route::get('/messages','messages');
        Route::get('/app-calender','app_calender');
        Route::get('/app-profile','app_profile'); // сделать форму более подходящей к настоящему использованию. кнопку заблокировать заменить на редактировать и убрать остальныке, имейл убрать нафиг. адерса разделить на юридический и фактический адрес.
        Route::get('/edit-profile','edit_profile');
        Route::match(['get','post'], '/post-details','post_details');
        Route::get('/chart-chartist','chart_chartist');
        Route::get('/chart-chartjs','chart_chartjs');
        Route::get('/chart-flot','chart_flot');
        Route::get('/chart-morris','chart_morris');
        Route::get('/chart-peity','chart_peity');
        Route::get('/chart-sparkline','chart_sparkline');
        Route::get('/ecom-checkout','ecom_checkout');
        Route::get('/ecom-customers','ecom_customers');
        Route::get('/ecom-invoice','ecom_invoice');
        Route::get('/ecom-product-detail','ecom_product_detail');
        Route::get('/ecom-product-grid','ecom_product_grid');
        Route::get('/ecom-product-list','ecom_product_list');
        Route::get('/ecom-product-order','ecom_product_order');
        Route::match(['get','post'], '/email-compose','email_compose');
        Route::get('/email-inbox','email_inbox');
        Route::get('/email-read','email_read');
        Route::get('/form-editor-ckeditor','form_ckeditor');
        Route::get('/form-element','form_element');
        Route::get('/form-pickers','form_pickers');
        Route::get('/form-validation-jquery','form_validation_jquery');
        Route::get('/form-wizard','form_wizard');
        Route::get('/map-jqvmap','map_jqvmap');
        Route::get('/page-error-400','page_error_400');
        Route::get('/page-error-403','page_error_403');
        Route::get('/page-error-404','page_error_404');
        Route::get('/page-error-500','page_error_500');
        Route::get('/page-error-503','page_error_503');
        Route::get('/page-forgot-password','page_forgot_password');
        Route::get('/page-lock-screen','page_lock_screen');
        Route::get('/page-login','page_login');
        Route::get('/page-register','page_register');
        Route::get('/table-bootstrap-basic','table_bootstrap_basic');
        Route::get('/table-datatable-basic','table_datatable_basic');
        Route::get('/uc-lightgallery','uc_lightgallery');
        Route::get('/uc-nestable','uc_nestable');
        Route::get('/uc-noui-slider','uc_noui_slider');
        Route::get('/uc-select2','uc_select2');
        Route::get('/uc-sweetalert','uc_sweetalert');
        Route::get('/uc-toastr','uc_toastr');
        Route::get('/ui-accordion','ui_accordion');
        Route::get('/ui-alert','ui_alert');
        Route::get('/ui-badge','ui_badge');
        Route::get('/ui-button','ui_button');
        Route::get('/ui-button-group','ui_button_group');
        Route::get('/ui-card','ui_card');
        Route::get('/ui-carousel','ui_carousel');
        Route::get('/ui-dropdown','ui_dropdown');
        Route::get('/ui-grid','ui_grid');
        Route::get('/ui-list-group','ui_list_group');
        Route::get('/ui-media-object','ui_media_object');
        Route::get('/ui-modal','ui_modal');
        Route::get('/ui-pagination','ui_pagination');
        Route::get('/ui-popover','ui_popover');
        Route::get('/ui-progressbar','ui_progressbar');
        Route::get('/ui-tab','ui_tab');
        Route::get('/ui-typography','ui_typography');
        Route::get('/widget-basic','widget_basic');
        Route::post('/ajax/recent-activities','recent_activities_ajax');
        Route::post('/ajax/contacts','contacts_ajax');
    });
});

//Route::post('/process-foundation-smeta-order', [OrderController::class, 'processFoundationSmetaOrder'])->name('process-foundation-smeta-order');
Route::post('/process-project-smeta-order', [OrderController::class, 'processProjectSmetaOrder'])->name('process-project-smeta-order');
Route::post('/process-example-smeta-order', [OrderController::class, 'processExampleSmetaOrder'])->name('process-example-smeta-order');
Route::post('/process-foundation-order', [OrderController::class, 'processFoundationOrder'])->name('process-foundation-order');
Route::post('/process-example-foundation-order', [OrderController::class, 'processExampleFoundationOrder'])->name('process-example-foundation-order');
Route::post('/process-membership-order', [OrderController::class, 'processMembershipOrder'])->name('process-membership-order');

Route::post('/send-feedback', [FeedbackController::class, 'send'])->name('send.feedback');

Route::post('/generate-excel', [TemplateController::class, 'generateExcel'])->name('generate-excel');
//route group prefix tmp
Route::prefix('tmp')->group(function () {
    Route::get('/', [TemplateController::class, 'index']);
    Route::get('/external', [FulfillmentController::class, 'process']);
    Route::get('/reindex-prices/{count}', [FulfillmentController::class, 'processLatestProjects'])->name('reindex-prices');
    Route::post('/store-template', [TemplateController::class, 'storeTemplate'])->name('store-template');
    Route::put('/update-template/{id}', [TemplateController::class, 'updateTemplate'])->name('update-template');
    Route::get('/get-template', [TemplateController::class, 'getTemplate']);
    Route::get('/download-template/{category}', [TemplateController::class, 'downloadTemplate'])->name('download-template');
    Route::get('/process-order/{id}', function ($id) {
        $order = Project::find($id);
        $order->createSmeta($order->selected_configuration);
        return response()->download($order->filepath);
        //return response()->download($order->filepath)->middleware('cors');
    });
});
Route::get('/get-project-title', function (Request $request) {
    $id = $request->query('id');
    // Query the Designs table to get the project title based on the $id
    $project = Design::find($id);
    if ($project) {
        return response()->json(['success' => true, 'title' => $project->title]);
    } else {
        return response()->json(['success' => false]);
    }
});

Route::get('/get-project-id', function (Request $request) {
    $title = $request->query('title');
    // Query the database to find the project ID based on the title
    $project = DB::table('designs')->where('title', $title)->first();
    if ($project) {
        return response()->json(['success' => true, 'id' => $project->id]);
    } else {
        return response()->json(['success' => false]);
    }
});

// New route to check for sheetname in the invoice_structures table
Route::get('/get-sheetname', function (Request $request) {
    $name = $request->query('name');
    // Query the database to find the sheetname
    $sheet = DB::table('invoice_structures')->where('sheetname', $name)->first();
    if ($sheet) {
        return response()->json(['success' => true, 'name' => $sheet->label]);
    } else {
        return response()->json(['success' => false]);
    }
});

// New route to get sheetname suggestions
Route::get('/get-sheetname-suggestions', function (Request $request) {
    $query = $request->query('query');
    // Query the database to find matching sheetnames
    $suggestions = DB::table('invoice_structures')
        ->where('sheetname', 'like', '%' . $query . '%')
        ->orWhere('label', 'like', '%' . $query . '%')
        ->pluck('sheetname');
    return response()->json(['success' => true, 'suggestions' => $suggestions]);
});

Route::get('/payment/set-status/{payment_status}/{order_id}', [OrderController::class, 'setPaymentStatus'])->name('payment.set.status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'getConversation'])->name('messages.conversation');
    Route::post('/messages', [MessageController::class, 'sendMessage'])->name('messages.send');
    Route::put('/messages/{messageId}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    Route::put('/messages/{id}/unread', [MessageController::class, 'markAsUnread'])->name('messages.markAsUnread');
    Route::put('/messages/{messageId}/archive', [MessageController::class, 'archiveMessage'])->name('messages.archive');
    Route::get('/profile', [ProfileController::class, 'settings'])->name('user.settings');
    Route::put('/profile', [ProfileController::class, 'updateSettings'])->name('user.updateSettings');
    Route::get('/my-account', [UIController::class, 'my_account'])->name('my.account');
    Route::get('/my-orders', [DesignController::class, 'getDemoOrder']);
    Route::get('/my-orders/view/{id}', [ProjectController::class, 'view'])->name('orders.view');
    Route::get('/my-orders/{payment_status}/{order_id}', [DesignController::class, 'getDemoOrderNew'])->name('payment.status');
    Route::get('/fiscal-receipt/{id}', [OrderController::class, 'viewFiscalReceipt'])->name('fiscal.receipt');
    Route::get('/general-receipt/{id}', [OrderController::class, 'viewGeneralReceipt'])->name('general.receipt');
});
Route::get('/get-foundation-file', [FulfillmentController::class, 'foundationFullFile']);
Auth::routes();
Route::get('/experimental-main', [TemplateController::class, 'experimentalMain'])->name('experimental-main');


//executors
Route::get('/assign-executor', [SupplierController::class, 'assignExecutorModal'])->name('assign.executor');
Route::get('/assign-executor-modal', [SupplierController::class, 'assignExecutorModal'])->name('assign.executor.modal');
Route::get('/available-executors/{projectId}', [SupplierController::class, 'getAvailableExecutors']);
Route::get('/get-executors', [SupplierController::class, 'getExecutors'])->name('get.executors');

//legal entity registration request
Route::post('/legal-entity-registration-form', [RegisterController::class, 'newRegistrationFullForm'])->name('legal-entity.confirm');
Route::get('/legal-entity-complete-registration', [RegisterController::class, 'completeRegistration'])->name('legal-entity.complete-registration');
Route::post('/register-legal-entity', [RegisterController::class, 'registerLegalEntity'])->name('new-legal-entity.register');

Route::get('/increment-redis-counter', function (Request $request) {
    $currentViews = Redis::get($request->query('key')) ?? 0;
    Redis::set($request->query('key'), $currentViews+1);
    return response()->json(['success' => true]);
});

Route::get('/messages/{userId}/new', [MessageController::class, 'getNewMessages']);
Route::post('/messages', [MessageController::class, 'store']);
Route::get('/messages/{userId}', [MessageController::class, 'getConversation']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/view-profile/{id}', [SupplierController::class, 'viewProfile'])->name('supplier.profile');
Route::put('/update-profile/{id}', [SupplierController::class, 'updateProfile'])->name('update.profile');



//static pages
Route::get('/terms-and-conditions', function () {
    return view('statics.terms');
})->name('terms.and.conditions');

//blogs
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');


//admin routes
Route::prefix('admin')->middleware(['auth', 'can:access-admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/edit-captions', [AdminController::class, 'editCaptions'])->name('admin.edit-captions');
    Route::post('/update-captions', [AdminController::class, 'updateCaptions'])->name('admin.update-captions');
});

//Route::post('/create-yandex-pay-order', [OrderController::class, 'processProjectSmetaOrder']);

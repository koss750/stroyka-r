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
use App\Http\Controllers\FoundationPlanController;
use App\Http\Controllers\Auth\ForgotPasswordController;
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
    return view('index');
});

Route::get('/site', function () {
    return view('index');
});

Route::get('/clear-cache', function () {
    Cache::flush();
    return view('index');
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

Route::get('/register-legal', function () {
    return view('register', [
        'type' => 'legal'
    ]);
});

//Route::get('/register-legal', [RegisterController::class, 'bringLoggedInUserRegistrationForm'])->name('register-legal');
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

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])
    ->name('password.update');

//Route::post('/process-foundation-smeta-order', [OrderController::class, 'processFoundationSmetaOrder'])->name('process-foundation-smeta-order');
Route::post('/process-project-smeta-order', [OrderController::class, 'processProjectSmetaOrder'])->name('process-project-smeta-order');
Route::post('/process-example-smeta-order', [OrderController::class, 'processExampleSmetaOrder'])->name('process-example-smeta-order');
Route::post('/process-foundation-order', [OrderController::class, 'processFoundationOrder'])->name('process-foundation-order');
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

Route::get('/foundation-plan', [FoundationPlanController::class, 'index'])->name('foundation.plan');
Route::post('/foundation-plan/save', [FoundationPlanController::class, 'store'])->name('foundation.plan.store');
Route::get('/foundation-plan/{foundationPlan}', [FoundationPlanController::class, 'show'])->name('foundation.plan.show');
Route::delete('/foundation-plan/{foundationPlan}', [FoundationPlanController::class, 'destroy'])->name('foundation.plan.destroy');

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
    Route::post('/profile', [ProfileController::class, 'updateSettings'])->name('user.update');
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
Route::post('/legal-entity-registration-form-profile', [ProfileController::class, 'profileLegalEntityConfirmation'])->name('legal-entity.confirm.profile');
Route::get('/legal-entity-complete-registration', [RegisterController::class, 'completeRegistration'])->name('legal-entity.complete-registration');
Route::post('/register-legal-entity', [RegisterController::class, 'registerLegalEntity'])->name('new-legal-entity.register');
Route::get('/registration-success', function () {
    return view('review');
})->name('registration.success');
Route::get('/registration-fail', function () {
    return view('review-fail');
})->name('registration.fail');

Route::get('/increment-redis-counter', function (Request $request) {
    $currentViews = Redis::get($request->query('key')) ?? 0;
    Redis::set($request->query('key'), $currentViews+1);
    return response()->json(['success' => true]);
});

Route::get('/messages/{userId}/new', [MessageController::class, 'getNewMessages']);
Route::get('/messages/{userId}', [MessageController::class, 'getConversation']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/view-profile/{id}', [SupplierController::class, 'viewProfile'])->name('supplier.profile');
Route::put('/update-profile/{id}', [SupplierController::class, 'updateProfile'])->name('update.profile');



//static pages
Route::get('/terms-and-conditions', function () {
    return view('statics.terms');
})->name('terms.and.conditions');

Route::get('/confidentiality', function () {
    return view('statics.confidentiality');
})->name('confidentiality');

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

Route::post('/trigger-index', [DesignController::class, 'triggerIndex']);
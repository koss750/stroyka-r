<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DesignController as DC;
use App\Http\Controllers\RuTranslationController as Translator;
use App\Http\Controllers\InvoiceController as Invoice;
use App\Models\FormField;
use App\Models\Foundation;
use App\Models\PricePlan;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Redis;

class UIController extends Controller
{
	
	
    public function showProject(Request $request, $id)
    {
        $fullUrl = $request->fullUrl();
        //$count = Redis::get("visits:$fullUrl") ?? 0;
        $count = 10;
        $design = DC::show($id);
        $page_title = Translator::translate("project_page_title");
        $page_description = Translator::translate("listing_page_description");
        return view('vora.ecom.product_list', compact('page_title', 'page_description', 'count', $design));
    }

    public function getFoundationPrice($foundation_title) {
        $pricePlan = PricePlan::where('id', 7)->firstOrFail();
        $prices = $pricePlan->parameter_option;
        foreach ($prices as $price) {
            Log::info('price value: ' . $price['value'] . ' foundation title: ' . $foundation_title);
            if ($price['value'] == $foundation_title) {
                Log::info('price found: ' . $price['price']);
                return $price['price'];
            }
        }
        return 999;
    }

    public function lentaFoundationCalculator()
    {
        $formFields = FormField::where('form_type', 'lenta')->orderBy('order')->get();
        $foundation = Foundation::where('title', 'lenta')->firstOrFail();
        $foundation->image = 'https://xn--80ardojfh.com/images/foundation-lenta.jpg';
        $page_title = $foundation->site_title;
        $page_description = 'Рассчитайте стоимость фундамента - ' . $foundation->site_title;
        $price = $this->getFoundationPrice($foundation->site_title);
        return view('foundation.lenta', compact('formFields', 'page_title', 'page_description', 'foundation', 'price'));
    }

    public function SRPFoundationCalculator()
    {
        $formFields = FormField::where('form_type', 'srp')->orderBy('order')->get();
        $foundation = Foundation::where('title', 'svs')->firstOrFail();
        $foundation->image = 'https://xn--80ardojfh.com/images/foundation-srp.jpg';
        $page_title = 'Калькулятор свайно ростверкового фундамента с плитой перекрытия';
        $page_description = 'Расчёт свайно-ростверкового фундамента с плитой перекрытия';
        $price = $this->getFoundationPrice($foundation->site_title);
        return view('foundation.lenta', compact('formFields', 'page_title', 'page_description', 'foundation', 'price'));
    }

    public function SRFoundationCalculator()
    {
        $formFields = FormField::where('form_type', 'sr')->orderBy('order')->get();
        $foundation = Foundation::where('title', 'sv')->firstOrFail();
        $foundation->image = 'https://xn--80ardojfh.com/images/foundation-sr.jpg';
        $page_title = 'Калькулятор свайно ростверкового фундамента';
        $page_description = 'Расчёт свайно-ростверкового фундамента';
        $price = $this->getFoundationPrice($foundation->site_title);
        return view('foundation.lenta', compact('formFields', 'page_title', 'page_description', 'foundation', 'price'));
    }

    public function LPFoundationCalculator()
    {
        $formFields = FormField::where('form_type', 'lp')->orderBy('order')->get();
        $foundation = Foundation::where('title', 'pLenta')->firstOrFail();
        $foundation->image = 'https://xn--80ardojfh.com/images/foundation-lp.jpg';
        $page_title = 'Калькулятор ленточного фундамента с плитой перекрытия';
        $page_description = 'Рассчитайте стоимость ленточного плитного фундамента';
        $price = $this->getFoundationPrice($foundation->site_title);
        return view('foundation.lenta', compact('formFields', 'page_title', 'page_description', 'foundation', 'price'));
    }

    public function MPFoundationCalculator()
    {
        $formFields = FormField::where('form_type', 'mp')->orderBy('order')->get();
        $foundation = Foundation::where('title', 'plita')->firstOrFail();
        $foundation->image = 'https://xn--80ardojfh.com/images/foundation-mp.jpg';
        $page_title = 'Калькулятор фундамента монолитная плита';
        $page_description = 'Калькулятор фундамента монолитная плита';
        $price = $this->getFoundationPrice($foundation->site_title);
        return view('foundation.lenta', compact('formFields', 'page_title', 'page_description', 'foundation', 'price'));
    }
	
    // Dashboard
    public function dashboard_1(){
        $page_title = 'Dashboard';
        $page_description = 'Some description for the page';
        return view('vora.dashboard.index', compact('page_title', 'page_description'));
    }
    
    // Dashboard 2
    public function dashboard_2(){
        $page_title = 'Dashboard';
        $page_description = 'Some description for the page';
        return view('vora.dashboard.index_2', compact('page_title', 'page_description'));
    }
    
    // Page Projects
    public function projects(){
        $page_title = 'Projects';
        $page_description = 'Some description for the page';
		return view('vora.dashboard.projects', compact('page_title', 'page_description'));
    }
	
    public function my_account(){
        $page_title = 'Личный кабинет';
        $page_description = 'Мой аккаунт';
        return view('my_account', compact('page_title', 'page_description'));
    }

    // Kanban
    public function kanban(){
        $page_title = 'Kanban';
        $page_description = 'Some description for the page';

        return view('vora.dashboard.kanban', compact('page_title', 'page_description'));
    }
    // Calendar
    public function calendar(){
        $page_title = 'Calendar';
        $page_description = 'Some description for the page';

        return view('vora.dashboard.calendar', compact('page_title', 'page_description'));
    }
    // Messages
    public function messages(){
        $page_title = 'Сообщения';
        $page_description = 'Мои сообщения';

        return view('vora.dashboard.messages', compact('page_title', 'page_description'));
    }
    // Calendar
    public function app_calender(){
        $page_title = 'Calendar';
        $page_description = 'Some description for the page';
        return view('vora.app.calender', compact('page_title', 'page_description'));
    }
    
    // Profile
    public function app_profile(){
        $page_title = 'Поставщики';
        $page_description = 'Здесь все ваше';
        return view('vora.app.profile', compact('page_title', 'page_description'));
    }
    
    // Edit Profile
    public function edit_profile(){
        $page_title = 'Edit Profile';
        $page_description = 'Some description for the page';
        return view('vora.app.edit_profile', compact('page_title', 'page_description'));
    }

    // Post Details
    public function post_details(){
        $page_title = 'Post Details';
        $page_description = 'Some description for the page';
        return view('vora.app.post_details', compact('page_title', 'page_description'));
    }
	
    // Chart Chartist
    public function chart_chartist(){
        $page_title = 'Chart Chartist';
     $page_description = 'Some description for the page';
        return view('vora.chart.chartist', compact('page_title', 'page_description'));
    }
	
    // Chart Chartjs
    public function chart_chartjs(){
        $page_title = 'Chart Chartjs';
        $page_description = 'Some description for the page';
        return view('vora.chart.chartjs', compact('page_title', 'page_description'));
    }
	
    // Chart Flot
    public function chart_flot(){
        $page_title = 'Chart Flot';
        $page_description = 'Some description for the page';
        return view('vora.chart.flot', compact('page_title', 'page_description'));
    }
	
    // Chart Morris
    public function chart_morris(){
        $page_title = 'Chart Morris';
        $page_description = 'Some description for the page';
        return view('vora.chart.morris', compact('page_title', 'page_description'));
    }
	
    // Chart Peity
    public function chart_peity(){
        $page_title = 'Chart Peity';
        $page_description = 'Some description for the page';
        return view('vora.chart.peity', compact('page_title', 'page_description'));
    }
	
    // Chart Sparkline
    public function chart_sparkline(){
        $page_title = 'Chart Sparkline';
        $page_description = 'Some description for the page';
        return view('vora.chart.sparkline', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Checkout
    public function ecom_checkout(){
        $page_title = 'Checkout';
        $page_description = 'Some description for the page';
        return view('vora.ecom.checkout', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Customers
    public function ecom_customers(){
        $page_title = 'Ecom Customers';
        $page_description = 'Some description for the page';
        return view('vora.ecom.customers', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Invoice
    public function ecom_invoice(){
        $page_title = 'Invoice';
        $page_description = 'Some description for the page';
        return view('vora.ecom.invoice', compact('page_title', 'page_description'));
    }

    // Ecommerce Invoice
    public function identifySmeta($foundation="fnone",$comp="dOCBSos240",$roof="rSoft"){
        $foundationSection = false;
    }
    // Ecommerce Invoice
    public function str($smeta){
        $page_title = 'Invoice2';
        $page_description = 'Some description for the page';
        return view('vora.ecom.str', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Product Detail
    public function ecom_product_detail(){
        $page_title = 'Product Detail';
        $page_description = 'Some description for the page';
        return view('vora.ecom.product_detail', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Product Grid
    public function ecom_product_grid(){
        $page_title = 'Product Grid';
        $page_description = 'Some description for the page';
        return view('vora.ecom.product_grid', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Product List
    public function ecom_product_list(){
        $page_title = 'Product List';
        $page_description = 'Some description for the page';
        return view('vora.ecom.product_list', compact('page_title', 'page_description'));
    }
	
    // Ecommerce Product Order
    public function ecom_product_order(){
        $page_title = 'Product Order';
        $page_description = 'Some description for the page';
        return view('vora.ecom.product_order', compact('page_title', 'page_description'));
    }
	
    // Email Compose
    public function email_compose(){
        $page_title = 'Compose';
        $page_description = 'Some description for the page';
        return view('vora.message.compose', compact('page_title', 'page_description'));
    }
	
    // Email Inbox
    public function email_inbox(){
        $page_title = 'Inbox';
        $page_description = 'Some description for the page';
        return view('vora.message.inbox', compact('page_title', 'page_description'));
    }
	
    // Email Read
    public function email_read(){
        $page_title = 'Read';
        $page_description = 'Some description for the page';
        return view('vora.message.read', compact('page_title', 'page_description'));
    }
	
    // Form Editor Summernote
    public function form_ckeditor(){
        $page_title = 'CkEditor';
        $page_description = 'Some description for the page';
        return view('vora.form.editor_ckeditor', compact('page_title', 'page_description'));
	}
	
    // Form Element
    public function form_element(){
        $page_title = 'Form Element';
        $page_description = 'Some description for the page';
        return view('vora.form.element', compact('page_title', 'page_description'));
    }
	
    // Form Pickers
    public function form_pickers(){
        $page_title = 'Form Pickers';
        $page_description = 'Some description for the page';
        return view('vora.form.pickers', compact('page_title', 'page_description'));
    }
	
    // Form Validation Jquery
    public function form_validation_jquery(){
        $page_title = 'Form Validation';
        $page_description = 'Some description for the page';
        return view('vora.form.validation_jquery', compact('page_title', 'page_description'));
    }
	
    // Form Wizard
    public function form_wizard(){
        $page_title = 'Form Wizard';
        $page_description = 'Some description for the page';
        return view('vora.form.wizard', compact('page_title', 'page_description'));
    }
    
    	
    // Map Jqvmap
    public function map_jqvmap(){
        $page_title = 'Map Jqvmap';
        $page_description = 'Some description for the page';
        return view('vora.map.jqvmap', compact('page_title', 'page_description'));
    }
	
    // Page Error 400
    public function page_error_400(){
        $page_title = 'Page Error 400';
        $page_description = 'Some description for the page';
        return view('vora.page.error_400', compact('page_title', 'page_description'));
    }
	
    // Page Error 403
    public function page_error_403(){
        $page_title = 'Page Error 403';
        $page_description = 'Some description for the page';
        return view('vora.page.error_403', compact('page_title', 'page_description'));
    }
	
    // Page Error 404
    public function page_error_404(){
        $page_title = 'Page Error 404';
        $page_description = 'Some description for the page';
        return view('vora.page.error_404', compact('page_title', 'page_description'));
    }
	
    // Page Error 500
    public function page_error_500(){
        $page_title = 'Page Error 500';
        $page_description = 'Some description for the page';
        return view('vora.page.error_500', compact('page_title', 'page_description'));
    }
	
    // Page Error 503
    public function page_error_503(){
        $page_title = 'Page Error 503';
        $page_description = 'Some description for the page';
        return view('vora.page.error_503', compact('page_title', 'page_description'));
    }
	
    // Page Forgot Password
    public function page_forgot_password(){
        $page_title = 'Page Forgot Password';
        $page_description = 'Some description for the page';
        return view('vora.page.forgot_password', compact('page_title', 'page_description'));
    }
	
    // Page Lock Screen
    public function page_lock_screen(){
        $page_title = 'Page Lock Screen';
        $page_description = 'Some description for the page';
        return view('vora.page.lock_screen', compact('page_title', 'page_description'));
    }
	
    // Page Login
    public function page_login(){
        $page_title = 'Войти';
        $page_description = 'Вход для зарегестрированных пользователей';
        return view('vora.page.login', compact('page_title', 'page_description'));
    }
	
    // Page Register
    public function page_register(){
        $page_title = 'Регистрация';
        $page_description = 'Регистрация для клиентов';
        return view('vora.page.register', compact('page_title', 'page_description'));
    }

    // Page Register Supplier
    public function page_register_supplier(){
        $page_title = 'Регистрация поставщиков';
        $page_description = 'Регистрация для поставщиков';
        return view('vora.page.register', compact('page_title', 'page_description'));
    }

    // Page Register Contractor
    public function page_register_contractor(){
        $page_title = 'Регистрация';
        $page_description = 'Регистрация для исполнителей';
        return view('vora.page.register', compact('page_title', 'page_description'));
    }
	
    // Table Bootstrap Basic
    public function table_bootstrap_basic(){
        $page_title = 'Table Basic';
     $page_description = 'Some description for the page';
        return view('vora.table.table_bootstrap_basic', compact('page_title', 'page_description'));
    }
	
    // Table Datatable Basic
    public function table_datatable_basic(){
        $page_title = 'Table Datatable';
        $page_description = 'Some description for the page';
        return view('vora.table.datatable_basic', compact('page_title', 'page_description'));
    }
    // UC Nestable.
    public function uc_nestable(){
        $page_title = 'Nestable';
        $page_description = 'Some description for the page';
        return view('vora.uc.nestable', compact('page_title', 'page_description'));
    }
    // UC Lightgallery.
    public function uc_lightgallery(){
        $page_title = 'Lightgallery';
        $page_description = 'Some description for the page';
        return view('vora.uc.lightgallery', compact('page_title', 'page_description'));
    }
	
    // UC NoUi Slider
    public function uc_noui_slider(){
        $page_title = 'Noui Slider';
        $page_description = 'Some description for the page';
        return view('vora.uc.noui_slider', compact('page_title', 'page_description'));
    }
	
    // UC Select2
    public function uc_select2(){
        $page_title = 'Select2';
        $page_description = 'Some description for the page';
        return view('vora.uc.select2', compact('page_title', 'page_description'));
    }
	
    // UC Sweetalert
    public function uc_sweetalert(){
        $page_title = 'Sweetalert';
        $page_description = 'Some description for the page';
        return view('vora.uc.sweetalert', compact('page_title', 'page_description'));
    }
	
    // UC Toastr
    public function uc_toastr(){
        $page_title = 'Toastr';
        $page_description = 'Some description for the page';
        return view('vora.uc.toastr', compact('page_title', 'page_description'));
    }
	
    // Ui Accordion
    public function ui_accordion(){
        $page_title = 'Accordion';
        $page_description = 'Some description for the page';
        return view('vora.ui.accordion', compact('page_title', 'page_description'));
    }
	
    // Ui Alert
    public function ui_alert(){
        $page_title = 'Alert';
        $page_description = 'Some description for the page';
        return view('vora.ui.alert', compact('page_title', 'page_description'));
    }
	
    // Ui Badge
    public function ui_badge(){
        $page_title = 'Badge';
        $page_description = 'Some description for the page';
        return view('vora.ui.badge', compact('page_title', 'page_description'));
    }
	
    // Ui Button
    public function ui_button(){
        $page_title = 'Button';
        $page_description = 'Some description for the page';
        return view('vora.ui.button', compact('page_title', 'page_description'));
    }
	
    // Ui Button Group
    public function ui_button_group(){
        $page_title = 'Button Group';
        $page_description = 'Some description for the page';
        return view('vora.ui.button_group', compact('page_title', 'page_description'));
    }
	
    // Ui Card
    public function ui_card(){
        $page_title = 'Card';
        $page_description = 'Some description for the page';
        return view('vora.ui.card', compact('page_title', 'page_description'));
    }
	
    // Ui Carousel
    public function ui_carousel(){
        $page_title = 'Carousel';
        $page_description = 'Some description for the page';
        return view('vora.ui.carousel', compact('page_title', 'page_description'));
    }
	
    // Ui Dropdown
    public function ui_dropdown(){
        $page_title = 'Dropdown';
        $page_description = 'Some description for the page';
        return view('vora.ui.dropdown', compact('page_title', 'page_description'));
    }
	
    // Ui Grid
    public function ui_grid(){
        $page_title = 'Grid';
        $page_description = 'Some description for the page';
        return view('vora.ui.grid', compact('page_title', 'page_description'));
    }
	
    // Ui List Group
    public function ui_list_group(){
        $page_title = 'List Group';
        $page_description = 'Some description for the page';
        return view('vora.ui.list_group', compact('page_title', 'page_description'));
    }
	
    // Ui Media Object
    public function ui_media_object(){
        $page_title = 'Media Object';
        $page_description = 'Some description for the page';
        return view('vora.ui.media_object', compact('page_title', 'page_description'));
    }
	
    // Ui Modal
    public function ui_modal(){
        $page_title = 'Modal';
        $page_description = 'Some description for the page';
        return view('vora.ui.modal', compact('page_title', 'page_description'));
    }
	
    // Ui Pagination
    public function ui_pagination(){
        $page_title = 'Pagination';
        $page_description = 'Some description for the page';
        return view('vora.ui.pagination', compact('page_title', 'page_description'));
    }
	
    // Ui Popover
    public function ui_popover(){
        $page_title = 'Popover';
        $page_description = 'Some description for the page';
        return view('vora.ui.popover', compact('page_title', 'page_description'));
    }
	
    // Ui Progressbar
    public function ui_progressbar(){
        $page_title = 'Progressbar';
        $page_description = 'Some description for the page';
        return view('vora.ui.progressbar', compact('page_title', 'page_description'));
    }
	
    // Ui Tab
    public function ui_tab(){
        $page_title = 'Tab';
        $page_description = 'Some description for the page';
        return view('vora.ui.tab', compact('page_title', 'page_description'));
    }
	

    // Ui Typography
    public function ui_typography(){
        $page_title = 'Typography';
        $page_description = 'Some description for the page';
        return view('vora.ui.typography', compact('page_title', 'page_description'));
    }
	
    // Widget Basic
    public function widget_basic(){
        $page_title = 'Widget';
        $page_description = 'Some description for the page';
        return view('vora.widget.widget_basic', compact('page_title', 'page_description'));
    }

    // Ajax Recent Activity
    public function recent_activities_ajax(){
        return view('vora.ajax.recent_activities_ajax');
    }
    
    // Ajax Recent Activity
    public function contacts_ajax(){
        return view('vora.ajax.contacts_ajax');
    }
}

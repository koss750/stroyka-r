<?php

namespace App\Providers;

use App\Nova\CustomTab;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Http\Controllers\RuTranslationController as Translator;
use App\Models\Design as Design;
use Laravel\Nova\Events\ServingNova;
use App\Nova\Floor;
use App\Nova\Room;
use App\Nova\Design as NovaDesign;
use App\Nova\Dashboards\Main;
use App\Nova\FormField;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use App\Nova\AssociatedCost;
use App\Nova\Supplier;
use App\Nova\Contractor;
use App\Nova\User;
use App\Nova\ExcelSheet as ExcelResource;
use App\Nova\Order;
use App\Nova\Setting as SettingsOriginal;
//use App\Nova\DynamicPageCard;
use App\Nova\Cards\RedisKeysCard;
use Illuminate\Http\Request;
use App\Nova\DesignNonAdmin;
use App\Nova\DesignSeo;
use App\Nova\ProjectPriceViewer;
use App\Nova\ProjectType;
use App\Nova\Metrics\DesignPurchaseStats;
use App\Nova\DesignPurchaseStatistic;
use App\Nova\InvoiceTypeStatistic;
use App\Nova\BlogPost;
use App\Nova\PricePlan;
use App\Nova\PortalLog;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::auth(function ($request) {
            return Gate::check('viewNova', [$request->user()]);
        });

        Nova::mainMenu(function (Request $request) {
            return [

                MenuSection::dashboard(Main::class),

                MenuSection::make(Translator::translate('business_menu'), [
                    MenuItem::resource(NovaDesign::class),
                    MenuItem::resource(DesignPurchaseStatistic::class),
                    MenuItem::resource(Order::class),
                    MenuItem::resource(Supplier::class),
                    MenuItem::resource(User::class),
                    MenuItem::resource(BlogPost::class),
                    
                    //MenuItem::resource(AssociatedCost::class),
                ])->icon('library')->collapsable(),

                MenuSection::make("Настройки", [
                //MenuSection::make(Translator::translate('settings_menu'), [
                    MenuItem::resource(FormField::class),
                    MenuItem::resource(PricePlan::class),
                    MenuItem::resource(PortalLog::class),
                    /*MenuItem::resource(DesignNonAdmin::class),
                    MenuItem::resource(DesignSeo::class),
                    MenuItem::resource(SettingsOriginal::class),*/
                ])->icon('cog')->collapsable(),
            ];
        });

        Nova::serving(static function (ServingNova $event): void {
            Nova::remoteScript(asset('form.js'));
            Nova::remoteScript(asset('https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'));
        });
        
        Design::saving(function ($design) {
            
            $pairs = [];
            $pairs[] = ['srSam70', 'mrSam70'];
            $pairs[] = ['srIzospanAM', 'mrIzospanAM'];
            $pairs[] = ['srIzospanAM35', 'mrIzospanAM35'];
            $pairs[] = ['srLenta', 'mrLenta'];
            $pairs[] = ['srRokvul', 'mrRokvul'];
            $pairs[] = ['srIzospanB', 'mrIzospanB'];
            $pairs[] = ['srIzospanB35', 'mrIzospanB35'];
            
            foreach ($pairs as $pair) {
                $first = $pair[0];
                $second = $pair[1];
                if (!empty($design->$first) && empty($design->$second)) {
                $design->$second = $design->$first;
                }
                if (!empty($design->$second) && empty($design->$second)) {
                $design->$first = $design->$second;
                }
            }
            // Check if mrSam70 is empty and srSam70 is not, then pair them up
            // Add more conditions for other fields as needed
            //$design->setTitle($design);
        });
        
        

    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->superadmin;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
	    return [
            new CustomTab(),
	    ];
    }

    public function cards()
    {
        return [
            new DesignPurchaseStats,
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Nova::resources([
            Floor::class,
            Room::class,
        ]);
    }
}

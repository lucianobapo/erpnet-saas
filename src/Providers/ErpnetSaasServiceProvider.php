<?php

namespace ErpNET\Saas\Providers;

use App\User;
use ErpNET\Saas\v1\Entities\Teams\Team;
use ErpNET\Saas\v1\Services\Ux\Data\DataTabs;
use ErpNET\Saas\v1\Services\Ux\Tab;
use ErpNET\Saas\v1\Services\Ux\Tabs;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ErpNET\Saas\v1\Services\ErpnetSparkService;

class ErpnetSaasServiceProvider extends ServiceProvider
{
    /**
     * Automatically support two-factor authentication.
     *
     * @var bool
     */
    protected $twoFactorAuth = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->invoiceWith = [
            'vendor' => config('app.name'),
            'product' => 'ERPNet',
            'street' => 'PO Box 111',
            'location' => 'Your Town, 12345',
            'phone' => '555-555-5555',
        ];

        config([
            'app.locale'=>env('APP_LOCALE','pt_BR'),
            'app.timezone'=>env('APP_TIMEZONE','America/Sao_Paulo'),
            'repository.cache.enabled'=>env('REPOSITORY_CACHE',false),
        ]);

        $app = $this->app;

        $projectRootDir = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
        $routesDir = $projectRootDir."routes".DIRECTORY_SEPARATOR;
        $configPath = $projectRootDir . "config".DIRECTORY_SEPARATOR."erpnetSaas.php";
        $viewsPath = $projectRootDir . "resources".DIRECTORY_SEPARATOR."views";
        $translationsPath = $projectRootDir . "resources".DIRECTORY_SEPARATOR."lang";
        $assetsPath = $projectRootDir . "resources".DIRECTORY_SEPARATOR."assets";
        $publicPath = $projectRootDir . "public";

        $this->mergeConfigFrom($configPath, 'erpnetSaas');

        $this->loadViewsFrom($viewsPath, 'erpnetSaas');

        $this->loadTranslationsFrom($translationsPath, 'erpnetSaas');

        AliasLoader::getInstance()->alias("Spark", \ErpNET\Saas\v1\Services\ErpnetSparkService::class);

//        $this->publishes([
//            $projectRootDir.'node_modules/font-awesome/fonts' => public_path('fonts'),
//        ], 'erpnetWidgetResourceFonts');

        //Publish public build
        $this->publishes([
            $publicPath => public_path('/')
        ], 'publicSaas');

        //Publish assets
        $this->publishes([
            $assetsPath => resource_path('assets/vendor/erpnetSaas'),
            $projectRootDir.'gulpfileErpnetSaas.js' => base_path('gulpfileErpnetSaas.js'),
        ], 'assetsSaas');

        //Publish translations
        $this->publishes([
            $translationsPath => resource_path('lang/vendor/erpnetSaas'),
        ], 'translationsSaas');

        //Publish views
        $this->publishes([
            $viewsPath => resource_path('views/vendor/erpnetSaas'),
        ], 'viewsSaas');

        //Publish Terms
        $this->publishes([
            $projectRootDir.'terms.md' => base_path('terms.md')
        ], 'termsSaas');

        //Publish Config
        $this->publishes([
            $configPath => config_path('erpnetSaas.php')
        ], 'configSaas');

        //Bind Interfaces
        $bind = config('erpnetSaas.bindInterfaces');

        foreach ($bind as $interface=>$repository)
            if(interface_exists($interface)  && class_exists($repository))
                $app->bind($interface, $repository);

//        $app->bind($bindInterface, $bindRepository);
//        foreach (config('erpnetMigrates.tables') as $table => $config) {
//            $routePrefix = isset($config['routePrefix'])?$config['routePrefix']:str_singular($table);
//            $bindInterface = '\\ErpNET\\Models\\v1\\Interfaces\\'.(isset($config['bindInterface'])?$config['bindInterface']:(ucfirst($routePrefix).'Repository'));
//            $bindRepository = '\\ErpNET\\Models\\v1\\Repositories\\'.(isset($config['bindRepository'])?$config['bindRepository']:(ucfirst($routePrefix).'RepositoryEloquent'));
//
//            if(interface_exists($bindInterface)  && class_exists($bindRepository)){
//                $app->bind($bindInterface, $bindRepository);
//            }
//        }

        //Routing
        include $routesDir."api.php";
        include $routesDir."web.php";


        Schema::defaultStringLength(191);

        if (method_exists($this, 'customizeSpark')) {
            $this->customizeSpark();
        }

        if (method_exists($this, 'customizeRegistration')) {
            $this->customizeRegistration();
        }

        if (method_exists($this, 'customizeRoles')) {
            $this->customizeRoles();
        }

        if (method_exists($this, 'customizeProfileUpdates')) {
            $this->customizeProfileUpdates();
        }

        if (method_exists($this, 'customizeSubscriptionPlans')) {
            $this->customizeSubscriptionPlans();
        }

        if (method_exists($this, 'customizeSettingsTabs')) {
            $this->customizeSettingsTabs();
        }

        if (method_exists($this, 'customizeDataTabs')) {
            $this->customizeDataTabs();
        }

        if ($this->twoFactorAuth) {
            ErpnetSparkService::withTwoFactorAuth();
        }

        ErpnetSparkService::generateInvoicesWith($this->invoiceWith);
        ErpnetSparkService::retrieveUsersWith(function(){
            return new User([
                'name' => 'teste',
                'email' => 'teste',
                'subscriptions' => 'teste',
            ]);
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Dingo\Api\Provider\LaravelServiceProvider::class);
        $this->app->register(\ErpNET\Models\Providers\ErpnetModelsServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }


    /**
     * Customize general Spark options.
     *
     * @return void
     */
    protected function customizeSpark()
    {
        ErpnetSparkService::configure([
            'models' => [
                'teams' => Team::class,
            ]
        ]);
    }

    /**
     * Customize Spark's new user registration logic.
     *
     * @return void
     */
    protected function customizeRegistration()
    {
        // Spark::validateRegistrationsWith(function (Request $request) {
        //     return [
        //         'name' => 'required|max:255',
        //         'email' => 'required|email|unique:users',
        //         'password' => 'required|confirmed|min:6',
        //         'terms' => 'required|accepted',
        //     ];
        // });

        // Spark::validateSubscriptionsWith(function (Request $request) {
        //     return [
        //         'plan' => 'required',
        //         'terms' => 'required|accepted',
        //         'stripe_token' => 'required',
        //     ];
        // });

        // Spark::createUsersWith(function (Request $request) {
        //     // Return New User Instance...
        // });
    }

    /**
     * Customize the roles that may be assigned to team members.
     *
     * @return void
     */
    protected function customizeRoles()
    {
        ErpnetSparkService::defaultRole('member');

        ErpnetSparkService::roles([
            'admin' => 'Administrator',
            'member' => 'Member',
        ]);
    }

    /**
     * Customize the tabs on the settings screen.
     *
     * @return void
     */
    protected function customizeSettingsTabs()
    {
        ErpnetSparkService::settingsTabs()->configure(function ($tabs) {
            return [
                $tabs->profile(),
                $tabs->teams(),
                $tabs->security(),
                $tabs->subscription(),
                // $tabs->make('Name', 'view', 'fa-icon'),
            ];
        });

        ErpnetSparkService::teamSettingsTabs()->configure(function ($tabs) {
            return [
                $tabs->owner(),
                $tabs->membership(),
                // $tabs->make('Name', 'view', 'fa-icon'),
            ];
        });
    }

    /**
     * Customize the tabs on the Data screen.
     *
     * @return void
     */
    protected function customizeDataTabs()
    {
        ErpnetSparkService::dataTabs()->configure(function (DataTabs $tabs) {
            return [
                $tabs->employee(),
                $tabs->internalCourse(),
                $tabs->externalCourse(),
                $tabs->supplier(),
                // $tabs->make('Name', 'view', 'fa-icon'),
            ];
        });

    }

    /**
     * Customize Spark's profile update logic.
     *
     * @return void
     */
    protected function customizeProfileUpdates()
    {
        // Spark::validateProfileUpdatesWith(function (Request $request) {
        //     return [
        //         'name' => 'required|max:255',
        //         'email' => 'required|email|unique:users,email,'.$request->user()->id,
        //     ];
        // });

        // Spark::updateProfilesWith(function (Request $request) {
        //     // Update $request->user()...
        // });
    }

    /**
     * Customize the subscription plans for the application.
     *
     * @return void
     */
    protected function customizeSubscriptionPlans()
    {
        ErpnetSparkService::free()
            ->features([
                'Compras',
                'Vendas',
                'Estoque',
            ]);

        ErpnetSparkService::plan('Assinatura Básico', 'spark-test-1')
            ->price(49.90)
            ->trialDays(15)
            ->features([
                'Compras',
                'Vendas',
                'Estoque',
            ]);
        ErpnetSparkService::plan('Assinatura Pro', 'spark-test-2')
            ->price(89.90)
            ->features([
                'Compras',
                'Vendas',
                'Estoque',
            ]);
    }
}

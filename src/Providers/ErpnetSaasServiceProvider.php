<?php

namespace ErpNET\Saas\Providers;

use ErpNET\Saas\v1\Entities\Teams\Team;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ErpNET\Saas\v1\Services\ErpnetSparkService;

class ErpnetSaasServiceProvider extends ServiceProvider
{

    /**
     * Meta-data included in invoices generated by Spark.
     *
     * @var array
     */
    protected $invoiceWith = [
        'vendor' => 'IlhaNET',
        'product' => 'ERPNet',
        'street' => 'PO Box 111',
        'location' => 'Your Town, 12345',
        'phone' => '555-555-5555',
    ];



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
        $app = $this->app;

        $projectRootDir = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
        $routesDir = $projectRootDir."routes".DIRECTORY_SEPARATOR;

        $configPath = $projectRootDir . 'config/erpnetSaas.php';
        $this->mergeConfigFrom($configPath, 'erpnetSaas');

        //Publish Config
        $this->publishes([
            $projectRootDir.'config/erpnetSaas.php' => config_path('erpnetSaas.php')
        ], 'configSaas');

        //Bind Interfaces
        $bind = config('erpnetSaas.bindInterfaces');
        dd($bind);

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

        if ($this->twoFactorAuth) {
            ErpnetSparkService::withTwoFactorAuth();
        }

        ErpnetSparkService::generateInvoicesWith($this->invoiceWith);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Dingo\Api\Provider\LaravelServiceProvider::class);
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

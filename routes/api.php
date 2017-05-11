<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Dingo\Api\Routing\Router;
use ErpNET\Saas\v1\Services\ErpnetSparkService;

$router = app(Router::class);

$routeConfigV1 = [
    'middleware' => 'cors',
    'namespace' => 'ErpNET\Saas\v1\Controllers',
//            'prefix' => $this->app['config']->get('debugbar.route_prefix'),
];

$router
    ->version('v1', function (Router $router) use ($routeConfigV1) {
        $router
            ->group($routeConfigV1, function (Router $router) use ($routeConfigV1) {

                // Stripe Routes...
                if (count(ErpnetSparkService::plans()) > 0) {
                    $router->post('stripe/webhook', 'Stripe\WebhookController@handleWebhook');
                }
                $router->get('config/{file}', ['as'=>'config', 'uses'=>function ($file) {
                    return response()->json([
                        'data' => config($file),
                    ]);
                }]);
                $router->get('lang/{locale}/{group}', ['as'=>'lang', 'uses'=>function ($locale, $group) {
                    $projectRootDir = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
                    $translationsPath = $projectRootDir . "resources".DIRECTORY_SEPARATOR."lang/";

                    $resourceFile = resource_path("lang" . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $group.'.php');
                    $vendorFile = $translationsPath . $locale . DIRECTORY_SEPARATOR . $group.'.php';

                    $file = null;
                    if (file_exists($resourceFile)) $file = $resourceFile;
                    elseif (file_exists($vendorFile)) $file = $vendorFile;

                    return response()->json([
                        'data' =>  is_null($file)?[]:require $file,
                    ]);
                }]);
            });
    });
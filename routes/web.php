<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Routing\Router;
use ErpNET\Saas\v1\Services\ErpnetSparkService;

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::resource('partners', '\ErpNET\Models\Controllers\PartnersController');

$routeConfig = [
    'namespace' => 'ErpNET\Saas\v1\Controllers',
//            'prefix' => $this->app['config']->get('debugbar.route_prefix'),
];

$router = app(Router::class);

$router->group($routeConfig, function(Router $router) {

// Profile Dashboard
//    $router->get('home', 'HomeController@show');

    // Terms Routes...
    $router->get('terms', 'TermsController@show');

    // Settings Dashboard Routes...
    $router->get('settings', 'Settings\DashboardController@show');

    // Profile Routes...
    $router->put('settings/user', 'Settings\ProfileController@updateUserProfile');

    // Team Routes...
    if (ErpnetSparkService::usingTeams()) {
        $router->post('settings/teams', 'Settings\TeamController@store');
        $router->get('settings/teams/{id}', 'Settings\TeamController@edit');
        $router->put('settings/teams/{id}', 'Settings\TeamController@update');
        $router->delete('settings/teams/{id}', 'Settings\TeamController@destroy');
        $router->get('settings/teams/switch/{id}', 'Settings\TeamController@switchCurrentTeam');

        $router->post('settings/teams/{id}/invitations', 'Settings\InvitationController@sendTeamInvitation');
        $router->post('settings/teams/invitations/{invite}/accept', 'Settings\InvitationController@acceptTeamInvitation');
        $router->delete('settings/teams/invitations/{invite}', 'Settings\InvitationController@destroyTeamInvitationForUser');
        $router->delete('settings/teams/{team}/invitations/{invite}', 'Settings\InvitationController@destroyTeamInvitationForOwner');

        $router->put('settings/teams/{team}/members/{user}', 'Settings\TeamController@updateTeamMember');
        $router->delete('settings/teams/{team}/members/{user}', 'Settings\TeamController@removeTeamMember');
        $router->delete('settings/teams/{team}/membership', 'Settings\TeamController@leaveTeam');
    }

    // Security Routes...
    $router->put('settings/user/password', 'Settings\SecurityController@updatePassword');
    $router->post('settings/user/two-factor', 'Settings\SecurityController@enableTwoFactorAuth');
    $router->delete('settings/user/two-factor', 'Settings\SecurityController@disableTwoFactorAuth');

    // Subscription Routes...
    if (count(ErpnetSparkService::plans()) > 0) {
        $router->post('settings/user/plan', 'Settings\SubscriptionController@subscribe');
        $router->put('settings/user/plan', 'Settings\SubscriptionController@changeSubscriptionPlan');
        $router->delete('settings/user/plan', 'Settings\SubscriptionController@cancelSubscription');
        $router->post('settings/user/plan/resume', 'Settings\SubscriptionController@resumeSubscription');
        $router->put('settings/user/card', 'Settings\SubscriptionController@updateCard');
        $router->put('settings/user/vat', 'Settings\SubscriptionController@updateExtraBillingInfo');
        $router->get('settings/user/plan/invoice/{id}', 'Settings\SubscriptionController@downloadInvoice');
    }

    // Two-Factor Authentication Routes...
    if (ErpnetSparkService::supportsTwoFactorAuth()) {
        $router->get('login/token', 'Auth\AuthController@showTokenForm');
        $router->post('login/token', 'Auth\AuthController@token');
    }

    // User API Routes...
    $router->get('spark/api/users/me', 'API\UserController@getCurrentUser');

    // Team API Routes...
    if (ErpnetSparkService::usingTeams()) {
        $router->get('spark/api/teams/invitations', 'API\InvitationController@getPendingInvitationsForUser');
        $router->get('spark/api/teams/roles', 'API\TeamController@getTeamRoles');
        $router->get('spark/api/teams/{id}', 'API\TeamController@getTeam');
        $router->get('spark/api/teams', 'API\TeamController@getAllTeamsForUser');
        $router->get('spark/api/teams/invitation/{code}', 'API\InvitationController@getInvitation');
    }

    // Subscription API Routes...
    if (count(ErpnetSparkService::plans()) > 0) {
        $router->get('spark/api/subscriptions/plans', 'API\SubscriptionController@getPlans');
        $router->get('spark/api/subscriptions/coupon/{code}', 'API\SubscriptionController@getCoupon');
        $router->get('spark/api/subscriptions/user/coupon', 'API\SubscriptionController@getCouponForUser');
    }
});
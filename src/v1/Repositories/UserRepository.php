<?php

namespace ErpNET\Auth\v1\Repositories;

use ErpNET\Saas\v1\Services\ErpnetSparkService;
use Illuminate\Http\Request;
use ErpNET\Saas\v1\Contracts\InteractsWithSparkHooks;
use ErpNET\Saas\v1\Contracts\Repositories\UserRepository as Contract;
use Illuminate\Support\Facades\DB;

class UserRepository implements Contract
{
    use InteractsWithSparkHooks;

    /**
     * Get the current user of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrentUser()
    {
        $user = ErpnetSparkService::user();

        if (ErpnetSparkService::usingTeams()) {
            $user->currentTeam;
        }

        $user->subscriptions;

        return $user->makeVisible(['card_brand', 'card_last_four', 'extra_billing_info']);
    }

    /**
     * Create a new user of the application based on a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function createUserFromRegistrationRequest(Request $request, $withSubscription = false)
    {
        return DB::transaction(function () use ($request, $withSubscription) {
            $user = $this->createNewUser($request, $withSubscription);

            if ($withSubscription) {
                $this->createSubscriptionOnStripe($request, $user);
            }

            return $user;
        });
    }

    /**
     * Create a new user of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function createNewUser(Request $request, $withSubscription)
    {
        if (ErpnetSparkService::$createUsersWith) {
            return $this->callCustomUpdater(Spark::$createUsersWith, $request, [$withSubscription]);
        } else {
            return $this->createDefaultUser($request);
        }
    }

    /**
     * Create the default user instance for a new registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function createDefaultUser(Request $request)
    {
        $model = config('auth.providers.users.model');

        return (new $model)->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }

    /**
     * Create the subscription on Stripe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function createSubscriptionOnStripe(Request $request, $user)
    {
        $plan = ErpnetSparkService::plans()->find($request->plan);

        $subscription = $user->newSubscription('main', $plan->id);

        if ($plan->hasTrial() && ! $user->stripe_id) {
            $subscription->trialDays($plan->trialDays);
        }

        if ($request->coupon) {
            $subscription->withCoupon($request->coupon);
        }

        if (ErpnetSparkService::$createSubscriptionsWith) {
            $this->callCustomUpdater(ErpnetSparkService::$createSubscriptionsWith, $request, [$user, $subscription, $stripeCustomer]);
        } else {
            $subscription->create($request->stripe_token);
        }
    }
}

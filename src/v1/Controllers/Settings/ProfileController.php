<?php

namespace ErpNET\Saas\v1\Controllers\Settings;

use ErpNET\Saas\v1\Contracts\InteractsWithSparkHooks;
use ErpNET\Saas\v1\Controllers\Controller;
use ErpNET\Saas\v1\Services\ErpnetSparkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ErpNET\Saas\v1\Events\User\ProfileUpdated;
use Illuminate\Foundation\Validation\ValidatesRequests;
use ErpNET\Saas\v1\Contracts\Repositories\UserRepository;

class ProfileController extends Controller
{
    use ValidatesRequests;
    use InteractsWithSparkHooks;

    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;

        $this->middleware('auth');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserProfile(Request $request)
    {
        $this->validateUserProfile($request);

        $originalEmail = Auth::user()->email;

        if (ErpnetSparkService::$updateProfilesWith) {
            $this->callCustomUpdater(ErpnetSparkService::$updateProfilesWith, $request);
        } else {
            Auth::user()->fill($request->all())->save();
        }

        if (Auth::user()->stripe_id && $originalEmail !== Auth::user()->email) {
            $this->updateStripeEmailAddress();
        }

        event(new ProfileUpdated(Auth::user()));

        return $this->users->getCurrentUser();
    }

    /**
     * Validate the incoming request to update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateUserProfile(Request $request)
    {
        if (ErpnetSparkService::$validateProfileUpdatesWith) {
            $this->callCustomValidator(
                ErpnetSparkService::$validateProfileUpdatesWith, $request
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email,'.Auth::id(),
            ]);
        }
    }

    /**
     * Update the user's e-mail address on Stripe.
     *
     * @return void
     */
    protected function updateStripeEmailAddress()
    {
        $customer = Auth::user()->asStripeCustomer();

        $customer->email = Auth::user()->email;

        $customer->save();
    }
}

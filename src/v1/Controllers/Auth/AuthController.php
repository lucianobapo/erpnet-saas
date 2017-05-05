<?php

namespace ErpNET\Saas\v1\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Exception;
use ErpNET\Saas\v1\Services\ErpnetSparkService;
use Illuminate\Http\Request;
use Stripe\Coupon as StripeCoupon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ErpNET\Saas\v1\Events\User\Registered;
use ErpNET\Saas\v1\Events\User\Subscribed;
use ErpNET\Saas\v1\Contracts\InteractsWithSparkHooks;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use ErpNET\Saas\v1\Events\Team\Created as TeamCreated;
use Illuminate\Foundation\Validation\ValidatesRequests;
use ErpNET\Saas\v1\Contracts\Repositories\UserRepository;
use ErpNET\Saas\v1\Contracts\Repositories\TeamRepository;

class AuthController extends Controller
{
    use AuthenticatesUsers, RegistersUsers, ThrottlesLogins, ValidatesRequests, InteractsWithSparkHooks;

    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * The team repository instance.
     *
     * @var TeamRepository
     */
    protected $teams;

    /**
     * The URI for the login route.
     *
     * @var string
     */
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @param  UserRepository  $users
     * @param  TeamRepository  $teams
     * @return void
     */
    public function __construct(UserRepository $users, TeamRepository $teams)
    {
        $this->users = $users;
        $this->teams = $teams;
        $this->plans = ErpnetSparkService::plans();

        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        if (ErpnetSparkService::supportsTwoFactorAuth() && ErpnetSparkService::twoFactorProvider()->isEnabled($user)) {
            return $this->logoutAndRedirectToTokenScreen($request, $user);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Generate a redirect response to the two-factor token screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\Response
     */
    protected function logoutAndRedirectToTokenScreen(Request $request, Authenticatable $user)
    {
        Auth::logout();

        $request->session()->put('spark:auth:id', $user->id);

        return redirect('login/token');
    }

    /**
     * Show the two-factor authentication token verification form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTokenForm()
    {
        return session('spark:auth:id') ? view('auth.token') : redirect('login');
    }

    /**
     * Verify the two-factor authentication token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function token(Request $request)
    {
        $this->validate($request, ['token' => 'required']);

        if (! session('spark:auth:id')) {
            return redirect('login');
        }

        $model = config('auth.providers.users.model');

        $user = (new $model)->findOrFail(
            $request->session()->pull('spark:auth:id')
        );

        if (ErpnetSparkService::twoFactorProvider()->tokenIsValid($user, $request->token)) {
            Auth::login($user);

            return redirect()->intended($this->redirectPath());
        } else {
            return back();
        }
    }

    /**
     * Show the application registration form.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        if (ErpnetSparkService::forcingPromotion() && ! $request->query('coupon')) {
            if (count($request->query()) > 0) {
                return redirect($request->fullUrl().'&coupon='.ErpnetSparkService::forcedPromotion());
            } else {
                return redirect($request->fullUrl().'?coupon='.ErpnetSparkService::forcedPromotion());
            }
        }

        if (count($this->plans->paid()) > 0) {
            return view('auth.registration.subscription');
        } else {
            return view('auth.registration.simple');
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $withSubscription = count($this->plans->paid()) > 0 &&
            $this->plans->find($request->plan) &&
            $this->plans->find($request->plan)->price > 0;

        return $this->handleRegistration($request, $withSubscription);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return \Illuminate\Http\Response
     */
    protected function handleRegistration(Request $request, $withSubscription = false)
    {
        $this->validateRegistration($request, $withSubscription);

        $user = $this->users->createUserFromRegistrationRequest(
            $request, $withSubscription
        );

        if ($request->team_name) {
            $team = $this->teams->create($user, ['name' => $request->team_name]);

            event(new TeamCreated($team));
        }

        if ($request->invitation) {
            $this->teams->attachUserToTeamByInvitation($request->invitation, $user);
        }

        event(new Registered($user));

        if ($withSubscription) {
            event(new Subscribed($user));
        }

        Auth::login($user);

        return response()->json(['path' => $this->redirectPath()]);
    }

    /**
     * Validate the new registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return void
     */
    protected function validateRegistration(Request $request, $withSubscription = false)
    {
        if (ErpnetSparkService::$validateRegistrationsWith) {
            $this->callCustomRegistrationValidator($request, $withSubscription);
        } else {
            $this->validateDefaultRegistration($request, $withSubscription);
        }
    }

    /**
     * Validate the new custom registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return void
     */
    protected function callCustomRegistrationValidator(Request $request, $withSubscription)
    {
        $validator = $this->getCustomValidator(
            ErpnetSparkService::$validateRegistrationsWith, $request, [$withSubscription]
        );

        if ($withSubscription) {
            $this->addSubscriptionRulesToValidator($validator, $request);
        }

        $this->callCustomValidator($validator, $request);
    }

    /**
     * Validate a new registration using the default rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $withSubscription
     * @return void
     */
    protected function validateDefaultRegistration(Request $request, $withSubscription)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'terms' => 'required|accepted',
        ]);

        if ($withSubscription) {
            $this->addSubscriptionRulesToValidator($validator, $request);
        }

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }

    /**
     * Add the subscription rules to the registration validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function addSubscriptionRulesToValidator($validator, Request $request)
    {
        $validator->mergeRules('stripe_token', 'required');

        if ($request->coupon) {
            $validator->after(function ($validator) use ($request) {
                $this->validateCoupon($validator, $request);
            });
        }
    }

    /**
     * Validate that the provided coupon actually exists on Stripe.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateCoupon($validator, Request $request)
    {
        try {
            $coupon = StripeCoupon::retrieve(
                $request->coupon, ['api_key' => config('services.stripe.secret')]
            );

            if ($coupon && $coupon->valid) {
                return;
            }
        } catch (Exception $e) {
            //
        }

        $validator->errors()->add('coupon', 'The provided coupon code is invalid.');
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return ErpnetSparkService::$afterAuthRedirectTo;
    }
}

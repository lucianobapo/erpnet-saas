<?php

namespace ErpNET\Saas\v1\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ErpNET\Saas\v1\Contracts\Repositories\TeamRepository;

class InvitationController extends Controller
{
    /**
     * The team data repository.
     *
     * @var TeamRepository
     */
    protected $teams;

    /**
     * Create a new controller instance.
     *
     * @param  TeamRepository  $teams
     * @return void
     */
    public function __construct(TeamRepository $teams)
    {
        $this->teams = $teams;

        $this->middleware('auth', ['except' => [
            'getInvitation',
        ]]);
    }

    /**
     * Get all of the pending invitations for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPendingInvitationsForUser(Request $request)
    {
        return $this->teams->getPendingInvitationsForUser($request->user());
    }

    /**
     * Get the invitation for the given code.
     *
     * Used to display coupon during registration.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getInvitation($code)
    {
        $model = config('auth.providers.users.model');

        $model = get_class((new $model)->invitations()
                    ->getQuery()->getModel());

        $invitation = (new $model)->with('team.owner')
                    ->where('token', $code)->firstOrFail();

        if ($invitation->isExpired()) {
            $invitation->delete();

            abort(404);
        }

        $invitation->team->setVisible(['name', 'owner']);

        $invitation->team->owner->setVisible(['name']);

        return $invitation;
    }
}

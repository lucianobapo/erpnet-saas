<?php

namespace ErpNET\Saas\v1\Controllers\Settings;

use ErpNET\Saas\v1\Contracts\InteractsWithSparkHooks;
use ErpNET\Saas\v1\Services\ErpnetSparkService;
use Illuminate\Http\Request;
use ErpNET\Saas\v1\Controllers\Controller;
use ErpNET\Saas\v1\Events\Team\Created as TeamCreated;
use ErpNET\Saas\v1\Events\Team\Deleting as DeletingTeam;
use Illuminate\Foundation\Validation\ValidatesRequests;
use ErpNET\Saas\v1\Contracts\Repositories\TeamRepository;

class TeamController extends Controller
{
    use ValidatesRequests;
    use InteractsWithSparkHooks;

    /**
     * The team repository instance.
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

        $this->middleware('auth');
    }

    /**
     * Create a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (ErpnetSparkService::$validateNewTeamsWith) {
            $this->callCustomValidator(
                ErpnetSparkService::$validateNewTeamsWith, $request
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
        }

        $team = $this->teams->create(
            $user, ['name' => $request->name]
        );

        event(new TeamCreated($team));

        return $this->teams->getAllTeamsForUser($user);
    }

    /**
     * Show the edit screen for a given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()->findOrFail($teamId);

        $activeTab = $request->get(
            'tab', ErpnetSparkService::firstTeamSettingsTabKey($team, $user)
        );

        return view('settings.team', compact('team', 'activeTab'));
    }

    /**
     * Update the team's owner information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        $this->validateTeamUpdate($request, $team);

        if (ErpnetSparkService::$updateTeamsWith) {
            $this->callCustomUpdater(ErpnetSparkService::$updateTeamsWith, $request, [$team]);
        } else {
            $team->fill(['name' => $request->name])->save();
        }

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Validate a team update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teams\Team
     * @return void
     */
    protected function validateTeamUpdate(Request $request, $team)
    {
        if (ErpnetSparkService::$validateTeamUpdatesWith) {
            $this->callCustomValidator(
                ErpnetSparkService::$validateTeamUpdatesWith, $request, [$team]
            );
        } else {
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
        }
    }

    /**
     * Switch the team the user is currently viewing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function switchCurrentTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()->findOrFail($teamId);

        $user->switchToTeam($team);

        return back();
    }

    /**
     * Update a team member on the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateTeamMember(Request $request, $teamId, $userId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)->findOrFail($teamId);

        $userToUpdate = $team->users->find($userId);

        if (! $userToUpdate) {
            abort(404);
        }

        $this->validateTeamMemberUpdate($request, $team, $userToUpdate);

        if (ErpnetSparkService::$updateTeamMembersWith) {
            $this->callCustomUpdater(ErpnetSparkService::$updateTeamMembersWith, $request, [$team, $userToUpdate]);
        } else {
            $userToUpdate->teams()->updateExistingPivot(
                $team->id, ['role' => $request->role]
            );
        }

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Validate a team update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateTeamMemberUpdate(Request $request, $team, $user)
    {
        if (ErpnetSparkService::$validateTeamMemberUpdatesWith) {
            $this->callCustomValidator(
                ErpnetSparkService::$validateTeamMemberUpdatesWith, $request, [$team, $user]
            );
        } else {
            $availableRoles = implode(
                ',', array_except(array_keys(ErpnetSparkService::roles()), 'owner')
            );

            $this->validate($request, [
                'role' => 'required|in:'.$availableRoles,
            ]);
        }
    }

    /**
     * Remove a team member from the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @param  string  $userId
     * @return \Illuminate\Http\Response
     */
    public function removeTeamMember(Request $request, $teamId, $userId)
    {
        $user = $request->user();

        $team = $user->teams()
                ->where('owner_id', $user->id)->findOrFail($teamId);

        $team->removeUserById($userId);

        return $this->teams->getTeam($user, $teamId);
    }

    /**
     * Remove the user from the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function leaveTeam(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $user->teams()
                    ->where('owner_id', '!=', $user->id)
                    ->where('id', $teamId)->firstOrFail();

        $team->removeUserById($user->id);

        return $this->teams->getAllTeamsForUser($user);
    }

    /**
     * Destroy the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $teamId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $teamId)
    {
        $user = $request->user();

        $team = $request->user()->teams()
                ->where('owner_id', $user->id)
                ->findOrFail($teamId);

        event(new DeletingTeam($team));

        $team->users()->where('current_team_id', $team->id)
                        ->update(['current_team_id' => null]);

        $team->users()->detach();

        $team->delete();

        return $this->teams->getAllTeamsForUser($user);
    }
}

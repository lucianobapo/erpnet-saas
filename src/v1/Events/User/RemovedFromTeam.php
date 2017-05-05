<?php

namespace ErpNET\Saas\v1\Events\User;

use ErpNET\Saas\v1\Entities\Teams\Team;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Auth\Authenticatable;

class RemovedFromTeam
{
    use SerializesModels;

    /**
     * The user instance.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * The team instance.
     *
     * @var Team
     */
    public $team;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  Team $team
     *
     * @return void
     */
    public function __construct(Authenticatable $user, Team $team)
    {
        $this->user = $user;
        $this->team = $team;
    }
}

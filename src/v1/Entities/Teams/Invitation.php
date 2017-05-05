<?php

namespace ErpNET\Auth\v1\Entities\Teams;

use ErpNET\Saas\v1\Services\ErpnetSparkService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitations';

    /**
     * The guarded attributes on the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get the team that owns the invitation.
     */
    public function team()
    {
        return $this->belongsTo(ErpnetSparkService::model('teams', Team::class), 'team_id');
    }

    /**
     * Determine if the coupon is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return Carbon::now()->subWeek()->gte($this->created_at);
    }
}

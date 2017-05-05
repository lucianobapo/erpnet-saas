<?php

namespace ErpNET\Saas\v1\Events\User;

use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Event, SerializesModels;
}

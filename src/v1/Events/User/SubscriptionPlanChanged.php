<?php

namespace ErpNET\Saas\v1\Events\User;

use Illuminate\Queue\SerializesModels;

class SubscriptionPlanChanged
{
    use Event, SerializesModels;
}

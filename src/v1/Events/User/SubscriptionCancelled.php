<?php

namespace ErpNET\Saas\v1\Events\User;

use Illuminate\Queue\SerializesModels;

class SubscriptionCancelled
{
    use Event, SerializesModels;
}

<?php

namespace ErpNET\Saas\v1\Services\Ux\Settings;

use ErpNET\Saas\v1\Services\ErpnetSparkService;

class DashboardTabs extends Tabs
{
    /**
     * Get the tab configuration for the "profile" tab.
     *
     * @return Tab
     */
    public function profile()
    {
        return new Tab('Profile', 'settings.tabs.profile', 'fa-user');
    }

    /**
     * Get the tab configuration for the "teams" tab.
     *
     * @return Tab
     */
    public function teams()
    {
        return new Tab('Teams', 'settings.tabs.teams', 'fa-users', function () {
            return ErpnetSparkService::usingTeams();
        });
    }

    /**
     * Get the tab configuration for the "security" tab.
     *
     * @return Tab
     */
    public function security()
    {
        return new Tab('Security', 'settings.tabs.security', 'fa-lock');
    }

    /**
     * Get the tab configuration for the "subscription" tab.
     *
     * @param  bool  $force
     * @return Tab|null
     */
    public function subscription($force = false)
    {
        return new Tab('Subscription', 'settings.tabs.subscription', 'fa-credit-card', function () use ($force) {
            return count(ErpnetSparkService::plans()->paid()) > 0 || $force;
        });
    }
}

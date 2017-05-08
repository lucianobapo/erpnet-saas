<?php
namespace ErpNET\Saas\v1\Services\Ux\Data;

use ErpNET\Saas\v1\Services\ErpnetSparkService;
use ErpNET\Saas\v1\Services\Ux\Tab;
use ErpNET\Saas\v1\Services\Ux\Tabs;

class DataTabs extends Tabs
{
    /**
     * Get the tab configuration.
     *
     * @return Tab
     */
    public function employee()
    {
        return new Tab(t('Employee'), 'data.tabs.employee', 'fa-user');
    }
    /**
     * Get the tab configuration.
     *
     * @return Tab
     */
    public function internalCourse()
    {
        return new Tab(t('Internal Course'), 'data.tabs.internalCourse', 'fa-user');
    }
    /**
     * Get the tab configuration.
     *
     * @return Tab
     */
    public function externalCourse()
    {
        return new Tab(t('External Course'), 'data.tabs.externalCourse', 'fa-user');
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

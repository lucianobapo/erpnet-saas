<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 07/05/17
 * Time: 19:51
 */

return [
    'bindInterfaces' => [
        \ErpNET\Saas\v1\Contracts\Repositories\UserRepository::class=>\ErpNET\Saas\v1\Repositories\UserRepository::class,
        \ErpNET\Saas\v1\Contracts\Repositories\TeamRepository::class=>\ErpNET\Saas\v1\Repositories\TeamRepository::class,
    ]
];
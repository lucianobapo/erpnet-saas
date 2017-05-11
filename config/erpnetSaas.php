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
    ],

    'defaultLocale' => env('APP_LOCALE', 'pt_BR'),
    'defaultMandante' => 'westgroup',
    'employeeApiUrl' => '/erpnet-api/partner',
    'employeeColumns' => [
        [ 'name' => 'id',  'displayName' => 'Id',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
        [ 'name' => 'nome', 'displayName' => 'Name',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
        [ 'name' => 'data_nascimento', 'displayName' => 'Birth date',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
        [ 'name' => 'observacao', 'displayName' => 'Note',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
    ],
];
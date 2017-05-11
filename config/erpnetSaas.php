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

    'defaultMandante' => 'westgroup',
    'employeeApiUrl' => '/erpnet-api/partner',
    'employeeColumns' => [
        [ 'name' => 'id',  'displayName' => 'Código',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
        [ 'name' => 'nome', 'displayName' => 'Nome',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
        [ 'name' => 'data_nascimento', 'displayName' => 'Data Nascimento',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
        [ 'name' => 'observacao', 'displayName' => 'Observação',
            'formInputType' => 'text',
            'formInputPlaceholder' => '',
            'newItemModel' => '',
            'fillItemModel' => ''
        ],
    ],
];
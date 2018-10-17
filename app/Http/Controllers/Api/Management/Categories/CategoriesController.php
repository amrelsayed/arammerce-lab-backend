<?php

namespace App\Http\Controllers\Api\Management\Categories;

class CategoriesController extends \AdminApiController
{
    /**
     * Controller info
     *
     * @var array
     */
    protected $controllerInfo = [
        'repository' => 'categories',
        'listOptions' => [
            'select' => ['id', 'name'],
        ],
        'rules' => [
            'all' => [
                'name' => 'required',
            ],
        ],
    ];
}

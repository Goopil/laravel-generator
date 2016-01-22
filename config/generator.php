<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scaffold settings
    |--------------------------------------------------------------------------
    |
    | Application layers consist of :
    |
    | Controllers - contains application logic and passing user input data to service
    | Services - The middleware between controller and repository,
    |     gather data from controller, performs validation and business logic, calling repositories for data manipulation.
    | Repositories - layer for interaction with models and performing DB operations
    | Request - laravel 5 request validation flow (validation pre controller)
    | pivots - scaffold pivots tables and add direct relation (hasMany) to models
    |
    */

    'use_repository_layer' => true,

    'use_service_layer'    => true,

    'use_request_layer'    => true,

    'pivot_scaffold' => false,

    /*
    |--------------------------------------------------------------------------
    | Base Classes
    |--------------------------------------------------------------------------
    |
    | This controller will be used as a base controller of all controllers
    |
     */
    'base_controller' => 'App\Http\Controllers\Controller',
    'base_controller_as' => '',

    'base_repository' => 'App\Entities\Repositories\Repository',
    'base_repository_as' => '',

    'base_service' => 'App\Entities\Services\Service',
    'base_service_as' => '',

    'base_request' => 'App\Http\Requests\Request',
    'base_request_as' => '',

    'base_model' => 'Illuminate\Database\Eloquent\Model',
    'base_model_as' => '',


    /*
    |--------------------------------------------------------------------------
    | Path for classes
    |--------------------------------------------------------------------------
    |
    | All Classes will be created on these relevant path
    |
     */
    'path_migration'  => base_path('database/migrations/'),
    
    'path_model'      => app_path('Entities/Models/'),
    
    'path_repository' => app_path('Entities/Repositories/'),
    
    'path_service'    => app_path('Entities/Services/'),
    
    'path_controller' => app_path('Http/Controllers/'),
    
    'path_view'       => base_path('resources/views/'),
    
    'path_request'    => app_path('Http/Requests/'),
    
    'path_route'      => app_path('Http/scaffold-routes.php'),

    'path_factory'    => base_path('database/factories/ModelFactory.php'),


    /*
    |--------------------------------------------------------------------------
    | Namespace for classes
    |--------------------------------------------------------------------------
    |
    | All Classes will be created with these namespaces
    |
     */
    'namespace_model'      => 'App\Entities\Models',
    
    'namespace_repository' => 'App\Entities\Repositories',
    
    'namespace_service'    => 'App\Entities\Services',
    
    'namespace_controller' => 'App\Http\Controllers',
    
    'namespace_request'    => 'App\Http\Requests',


    /*
    |--------------------------------------------------------------------------
    | View extend
    |--------------------------------------------------------------------------
     */
    'main_layout' => 'layouts.app',


    /*
    |--------------------------------------------------------------------------
    | Routes prefix
    |--------------------------------------------------------------------------
     */
    'route_prefix' => 'scaffold',


    /*
    |--------------------------------------------------------------------------
    | Message
    |--------------------------------------------------------------------------
     */
    'message' => [
        'en'  => [
            'store'     => ':model saved successfully.',
            'update'    => ':model updated successfully.',
            'delete'    => ':model deleted successfully.',
            'not_found' => ':model not found',
        ],
    ],
];

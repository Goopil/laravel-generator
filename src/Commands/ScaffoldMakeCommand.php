<?php

namespace Peaches\Generator\Commands;

use Peaches\Generator\Generators\ControllerGenerator;
use Peaches\Generator\Generators\ModelGenerator;
use Peaches\Generator\Generators\RepositoryGenerator;
use Peaches\Generator\Generators\RequestGenerator;
use Peaches\Generator\Generators\RoutesGenerator;
use Peaches\Generator\Generators\ServiceGenerator;
use Peaches\Generator\Generators\ViewGenerator;

class ScaffoldMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generator:make:scaffold
                                {tables?} : List table name for generate scaffold.}
                                {--tables= : List table name for generate scaffold.}
                                {--ignore= : List ignore table name.}
                                {--models= : List model name for generate.}
                                {--paginate=10 : Pagination for index.blade.php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full CRUD for given table with initial views.';
    /**
     * A list model name for generate
     *
     * @var array
     */
    public $models = [];

    /**
     * Get the type of command
     *
     * @return string
     */
    public function getType()
    {
        return 'scaffold';
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('models')) {
            $this->models = explode(',', $this->option('models'));
        }

        if ($this->option('models')) {
            $this->models = explode(',', $this->option('models'));
        }

        // TODO: compare the length option

        $configData = $this->getConfigData();

        // get message config by locale
        $locale = config('app.locale');
        $configMessages = config('generator.message');
        if (isset($configMessages[$locale])) {
            $messages = $configMessages[$locale];
        } else {
            $messages = $configMessages['en'];
        }
        $configData = array_merge([
            'MESSAGE_STORE' => "'" . str_replace(':model', '$MODEL_NAME$', $messages['store']) . "'",
            'MESSAGE_UPDATE' => "'" . str_replace(':model', '$MODEL_NAME$', $messages['update']) . "'",
            'MESSAGE_DELETE' => "'" . str_replace(':model', '$MODEL_NAME$', $messages['delete']) . "'",
            'MESSAGE_NOT_FOUND' => "'" . str_replace(':model', '$MODEL_NAME$', $messages['not_found']) . "'",
        ], $configData);

        // init generators
        $routeGenerator = new RoutesGenerator($this);
        $modelGenerator = new ModelGenerator($this);

        $useRepositoryLayer = config('generator.use_repository_layer', true);
        if ($useRepositoryLayer) {
            $repositoryGenerator = new RepositoryGenerator($this);
            $repositoryGenerator->askMakeBaseRepository();
        }

        $useServiceLayer = config('generator.use_service_layer', true);
        if ($useServiceLayer) {
            $serviceGenerator = new ServiceGenerator($this);
        }

        $controllerGenerator = new ControllerGenerator($this);
        $viewGenerator = new ViewGenerator($this);

        // generate files for every table
        foreach ($this->tables as $idx => $tableName) {
            if (isset($this->models[$idx])) {
                $modelName = $this->models[$idx];
            } else {
                $modelName = str_singular(studly_case($tableName));
            }

            $data = array_merge($configData, [
                'TABLE_NAME' => $tableName,
                'MODEL_NAME' => $modelName,
                'MODEL_NAME_CAMEL' => camel_case($modelName),
                'MODEL_NAME_PLURAL' => str_plural($modelName),
                'MODEL_NAME_PLURAL_CAMEL' => camel_case(str_plural($modelName)),
                'RESOURCE_URL' => str_slug($tableName),
                'VIEW_FOLDER_NAME' => snake_case($tableName),
            ]);

            // request handler
            $requestGenerator = new RequestGenerator($this);

            $useRequestLayer = config('generator.use_request_layer', true);

            $data = array_merge($data, $controllerGenerator->requestLayer($configData, $modelName,$useRequestLayer ));

            // create a model
            $modelGenerator->generate($data);

            // skip pivot if scaffold is disabled
            if(!config('generator.pivot_scaffold', false) && $modelGenerator->isPivots)
            {
                $this->comment('skipping scaffold for pivot model: ' . $tableName);
                continue;
            }

            $this->comment('Generating scaffold for: ' . $tableName);

            // update route
            $routeGenerator->generate($data);

            if (isset($repositoryGenerator)) {
                // create a repository
                $repositoryGenerator->generate($data);
            }

            if (isset($serviceGenerator)) {
                // create a service
                $serviceGenerator->generate($data);
            }

            if (isset($useRequestLayer)) {
                // create request files
                $requestGenerator->generate($data);
            }

            // create a controller
            $controllerGenerator->generate($data);

            // create a view folder
            $viewGenerator->fillableColumns = $modelGenerator->fillableColumns;
            $viewGenerator->generate($data);
        }
    }
}

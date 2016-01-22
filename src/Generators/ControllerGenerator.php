<?php

namespace Peaches\Generator\Generators;

class ControllerGenerator extends BaseGenerator implements GeneratorInterface
{
    /**
     * Get the type of command
     *
     * @return string
     */
    public function getType()
    {
        return 'controller';
    }

    /**
     * Get the template path for generate
     *
     * @return string
     */
    public function getTemplatePath()
    {
        // get template filename
        $useRepositoryLayer = config('generator.use_repository_layer', false);
        $useServiceLayer = config('generator.use_service_layer', false);

        if ($useServiceLayer && $useRepositoryLayer) {
            $templateFilename = 'Controller_Service';
        } elseif ($useRepositoryLayer) {
            $templateFilename = 'Controller_Repository';
        } else {
            $templateFilename = 'Controller_Request';
        }
        return 'scaffold/' . $templateFilename;
    }

    public function generate($data = [])
    {
        if ($this->command->option('paginate')) {
            $data['RENDER_TYPE'] = 'paginate(' . $this->command->option('paginate') . ')';
        } else {
            $data['RENDER_TYPE'] = 'all()';
        }

        $filename = $data['MODEL_NAME'] . 'Controller.php';
        $this->generateFile($filename, $data);
    }
}

<?php

namespace Peaches\Generator\Generators;

class ServiceGenerator extends BaseGenerator implements GeneratorInterface
{
    /**
     * Get the type of command
     *
     * @return string
     */
    public function getType()
    {
        return 'service';
    }

    /**
     * Get the template path for generate
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return 'scaffold/Service';
    }

    public function generate($data = [])
    {
        $filename = $data['MODEL_NAME'] . 'Service.php';

        $templateData = $this->getExtendsClass('service',$data);

        $this->generateFile($filename, $templateData);
    }
}

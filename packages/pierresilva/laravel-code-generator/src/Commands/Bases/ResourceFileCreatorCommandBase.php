<?php

namespace pierresilva\CodeGenerator\Commands\Bases;

use pierresilva\CodeGenerator\Commands\Bases\ResourceFileCommandBase;
use pierresilva\CodeGenerator\Support\Helpers;

class ResourceFileCreatorCommandBase extends ResourceFileCommandBase
{
    /**
     * Converts the current command's argument and options into an array.
     *
     * @return array
     */
    protected function getCommandOptions($input)
    {
        return [
            'model-name' => $this->argument('model-name'),
            '--resource-filename' => $this->option('resource-filename'),
            '--fields' => $this->option('fields'),
            '--translation-for' => $this->option('translation-for'),
            '--relations' => $this->option('relations'),
            '--indexes' => $this->option('indexes'),
        ];
    }

    /**
     * Gets a clean user inputs.
     *
     * @return object
     */
    protected function getCommandInput()
    {
        $modelName = trim($this->argument('model-name'));
        $filename = trim($this->option('resource-filename'));
        $file = $filename ? str_finish($filename, '.json') : Helpers::makeJsonFileName($modelName);
        $translationFor = array_unique(Helpers::convertStringToArray($this->option('translation-for')));
        $fieldNames = array_unique(Helpers::convertStringToArray($this->option('fields')));
        $relations = Helpers::convertStringToArray($this->option('relations'));
        $indexes = Helpers::convertStringToArray($this->option('indexes'));

        return (object) compact(
            'modelName',
            'file',
            'fieldNames',
            'indexes',
            'relations',
            'translationFor'
        );
    }

}

<?php

namespace pierresilva\CodeGenerator\Commands\Bases;

use pierresilva\CodeGenerator\Models\Resource;
use pierresilva\CodeGenerator\Support\Config;
use pierresilva\CodeGenerator\Traits\CommonCommand;
use Illuminate\Console\Command;

class ResourceFileCommandBase extends Command
{
    use CommonCommand;

    /**
     * Gets the resource from the given file
     *
     * @param string $file
     * @param array $languages
     *
     * @return pierresilva\CodeGenerator\Models\Resource
     */
    protected function getResources($file, array $languages = [])
    {
        return Resource::fromJson($this->getFileContent($file), 'pierresilva', $languages);
    }

    /**
     * Gets the destenation filename.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getFilename($name)
    {
        $path = base_path(Config::getResourceFilePath());

        return $path . $name;
    }

    /**
     * Display a common error
     *
     * @return void
     */
    protected function noResourcesProvided()
    {
        $this->error('Nothing to append was provided! Please use the --fields, --relations, or --indexes to append to file.');
    }
}

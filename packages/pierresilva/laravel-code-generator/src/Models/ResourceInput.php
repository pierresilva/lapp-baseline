<?php

namespace pierresilva\CodeGenerator\Models;

class ResourceInput
{
    /**
     * The fields name
     *
     * @var string
     */
    public $modelName;

    /**
     * The controller name
     *
     * @var string
     */
    public $controllerName;

    /**
     * The prefix
     *
     * @var string
     */
    public $prefix;

    /**
     * The language file
     *
     * @var string
     */
    public $languageFileName;

    /**
     * The table name
     *
     * @var string
     */
    public $table;

    /**
     * The views directory
     *
     * @var string
     */
    public $viewsDirectory;

    /**
     * Total models per page
     *
     * @var int
     */
    public $perPage = 25;

    /**
     * The fields file
     *
     * @var string
     */
    public $resourceFile;

    /**
     * With form-request
     *
     * @var bool
     */
    public $formRequest = false;

    /**
     * The controller directory
     *
     * @var string
     */
    public $controllerDirectory;

    /**
     * What should the controller extends
     *
     * @var string
     */
    public $controllerExtends;

    /**
     * Without migration
     *
     * @var bool
     */
    public $withMigration = false;

    /**
     * Override existing files
     *
     * @var bool
     */
    public $force = false;

    /**
     * Models directory
     *
     * @var string
     */
    public $modelDirectory;

    /**
     * Primary key
     *
     * @var string
     */
    public $primaryKey;

    /**
     * With soft delete
     *
     * @var bool
     */
    public $withSoftDelete;

    /**
     * Without time stamp
     *
     * @var bool
     */
    public $withoutTimeStamps;

    /**
     * migration class name
     *
     * @var string
     */
    public $migrationClass;

    /**
     * The name of the connection
     *
     * @var string
     */
    public $connectionName;

    /**
     * The database engine name
     *
     * @var string
     */
    public $engineName;

    /**
     * The name of the template to use
     *
     * @var string
     */
    public $template;

    /**
     * The name of the connection
     *
     * @var the name of the layout
     */
    public $layoutName;

    /**
     * Should the resources get generated from existing database.
     *
     * @var bool
     */
    public $tableExists;

    /**
     * The languages to generate languages for.
     *
     * @var string
     */
    public $translationFor;

    /**
     * Generate resources with Authentication.
     *
     * @var bool
     */
    public $withAuth;

    /**
     * The form-request directory
     *
     * @var string
     */
    public $formRequestDirectory;

    /**
     * Creates a new field instance.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->modelName = $name;
    }
}

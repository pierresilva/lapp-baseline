<?php

namespace pierresilva\CodeGenerator\Models;

use pierresilva\CodeGenerator\Support\Config;
use pierresilva\CodeGenerator\Support\Contracts\JsonWriter;
use pierresilva\CodeGenerator\Support\Helpers;
use pierresilva\CodeGenerator\Support\Str;
use pierresilva\CodeGenerator\Traits\CommonCommand;

class ForeignConstraint implements JsonWriter
{
    use CommonCommand;

    /**
     * The name of the foreign field/column.
     *
     * @var string
     */
    public $column;

    /**
     * The name of the column being referenced on the foreign model.
     *
     * @var string
     */
    public $references;

    /**
     * The name of the foreign model being referenced.
     *
     * @var string
     */
    public $on;

    /**
     * The action to take when the model is updated.
     *
     * @var string
     */
    public $onUpdate;

    /**
     * The action to take when the model is deleted.
     *
     * @var string
     */
    public $onDelete;

    /**
     * The model this key references
     *
     * @var string
     */
    protected $referencesModel;

    /**
     * If the model references itself.
     *
     * @var string
     */
    protected $isSelfReference;

    /**
     * Creates a new field instance.
     *
     * @param string $column
     * @param string $references
     * @param string $on
     * @param string $onDelete
     * @param string $onUpdate
     * @param string $model
     *
     * @return void
     */
    public function __construct($column, $references, $on, $onDelete = null, $onUpdate = null, $model = null, $isSelfReference = false)
    {
        $this->column = $column;
        $this->references = $references;
        $this->on = $on;
        $this->onUpdate = $onUpdate;
        $this->onDelete = $onDelete;
        $this->referencesModel = $model;
        $this->isSelfReference = $isSelfReference;
    }

    /**
     * Get the model that is being referenced.
     *
     * @return string
     */
    public function getReferencesModel()
    {
        if (empty($this->referencesModel)) {
            $this->referencesModel = $this->getModelNamespace(ucfirst($this->getForeignModelName()));
        }

        return $this->referencesModel;
    }

    /**
     * Checks if this constraint reference itself as parent-child relation
     *
     * @return bool
     */
    public function isSelfReference()
    {
        return $this->isSelfReference;
    }

    /**
     * Get a foreign relation.
     *
     * @return pierresilva\CodeGenerator\Models\ForeignRelatioship
     */
    public function getForeignRelation()
    {
        $params = [
            $this->getReferencesModel(),
            $this->column,
            $this->on,
        ];

        $prefix = $this->isSelfReference() ? 'parent_' : '';

        $relation = new ForeignRelationship(
            'belongsTo',
            $params,
            $this->getForeignModelName($prefix)
        );

        return $relation;
    }

    /**
     * Get the namecpase of the foreign model.
     *
     * @param string $modelName
     *
     * @return string
     */
    protected function getModelNamespace($modelName)
    {
        $path = Helpers::getAppNamespace() . rtrim(Config::getModelsPath(), '\\');

        return $path . '\\' . $modelName;
    }

    /**
     * Get the name of the foreign model.
     *
     * @param string $prefix
     *
     * @return string
     */
    protected function getForeignModelName($prefix = '')
    {
        return camel_case($prefix . Str::singular($this->references));
    }

    /**
     * Checks if the constraint has a delete action.
     *
     * @return bool
     */
    public function hasDeleteAction()
    {
        return !empty($this->onDelete);
    }

    /**
     * Checks if the constraint has an update action.
     *
     * @return bool
     */
    public function hasUpdateAction()
    {
        return !empty($this->onUpdate);
    }

    /**
     * Gets the constrain in an array format.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'field' => $this->column,
            'references' => $this->references,
            'on' => $this->on,
            'on-delete' => $this->onDelete,
            'on-update' => $this->onUpdate,
            'references-model' => $this->getReferencesModel(),
            'is-self-reference' => $this->isSelfReference(),
        ];
    }

    /**
     * Get the foreign constraints
     *
     * @param array $properties
     * @param string $fieldName
     *
     * @return null || pierresilva\CodeGenerator\Models\ForeignConstraint
     */
    public static function fromArray(array $constraint, $fieldName)
    {
        $onUpdate = Helpers::isKeyExists($constraint, 'on-update') ? $constraint['on-update'] : null;
        $onDelete = Helpers::isKeyExists($constraint, 'on-delete') ? $constraint['on-delete'] : null;
        $model = Helpers::isKeyExists($constraint, 'references-model') ? $constraint['references-model'] :
        Helpers::guessModelFullName($fieldName, Helpers::getModelsPath());
        $isSelfReference = Helpers::isKeyExists($constraint, 'is-self-reference') ? (bool) $constraint['is-self-reference'] : false;

        return new self(
            $constraint['field'],
            $constraint['references'],
            $constraint['on'],
            $onDelete,
            $onUpdate,
            $model,
            $isSelfReference
        );
    }
}

<?php

namespace App\Traits;

trait SchemaTrait {
    public function getTableColumns()
    {
        $schema = \DB::getDoctrineSchemaManager();
        $columns = $schema->listTableColumns($this->getTable());
        $foreignKeys = $schema->listTableForeignKeys($this->getTable());
        // dd($foreignKeys);
        $foreign = [];
        foreach ($foreignKeys as $foreignKey) {
            $foreign[] = $foreignKey->getName();
        }

        $columnList = [];
        foreach ($columns as $column) {
            $columnList[] = [
                'name' => $column->getName(),
                'type' => '' . $column->getType(),
                'comment' => $column->getComment(),
                'definition' => $column->getColumnDefinition(),
                'default' => $column->getDefault(),
                'autoincrement' => $column->getAutoincrement(),
                'length' => $column->getLength(),
                'notNull' => $column->getNotnull(),
            ];
        }

        return [
            'columns' => $columnList,
            'foreign' => $foreign,
        ];
    }
}

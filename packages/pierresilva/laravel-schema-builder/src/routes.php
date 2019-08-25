<?php

if ($isLumen) {
    // Load views for schema builder
    $app->get('schema-builder', 'SchemaBuilderController@index');

    // Generate database migration files
    $app->post('schema-builder', 'SchemaBuilderController@generateMigration');
} else {
    // Load views for schema builder
    Route::get('schema-builder', 'pierresilva\SchemaBuilderSchemaBuilderController@index');

    // Generate database migration files
    Route::post('schema-builder', 'SchemaBuilderController@generateMigration');
}

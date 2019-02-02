<?php

return [

    /*
     * Language lines will be fetched by these loaders. You can put any class here that implements
     * the pierresilva\TranslationLoader\TranslationLoaders\TranslationLoader-interface.
     */
    'translation_loaders' => [
        pierresilva\TranslationLoader\TranslationLoaders\Db::class,
    ],

    /*
     * This is the model used by the Db Translation loader. You can put any model here
     * that extends pierresilva\TranslationLoader\LanguageLine.
     */
    'model' => pierresilva\TranslationLoader\LanguageLine::class,

    /*
     * This is the translation manager which overrides the default Laravel `translation.loader`
     */
    'translation_manager' => pierresilva\TranslationLoader\TranslationLoaderManager::class,

];

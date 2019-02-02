<?php

namespace pierresilva\CodeGenerator\Support;

use Illuminate\Translation\Translator;
use Illuminate\Support\Arr;

class pierresilvaTranslator extends Translator
{
    /**
     * Add translation lines to the given locale.
     *
     * @param  array  $lines
     * @param  string  $locale
     * @param  string  $namespace
     * @return void
     */
    public function addLines(array $lines, $locale, $namespace = '*')
    {
        foreach ($lines as $key => $value) {
            list($group, $item) = explode('.', $key, 2);
            Arr::set($this->loaded, "$namespace.$group.$locale.$item", $value);
        }
    }

    /**
    * Adds a new instance of pierresilva_translator to the IoC container,
    *
    * @return pierresilva\CodeGenerator\Support\pierresilvaTranslator
    */
    public static function getTranslator()
    {
        $translator = app('translator');

        app()->singleton('pierresilva_translator', function ($app) use ($translator) {
            $trans = new pierresilvaTranslator($translator->getLoader(), $translator->getLocale());

            $trans->setFallback($translator->getFallback());

            return $trans;
        });

        return app('pierresilva_translator');
    }
}

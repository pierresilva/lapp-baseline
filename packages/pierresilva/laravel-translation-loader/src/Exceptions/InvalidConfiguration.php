<?php

namespace pierresilva\TranslationLoader\Exceptions;

use Exception;
use pierresilva\TranslationLoader\LanguageLine;

class InvalidConfiguration extends Exception
{
    public static function invalidModel(string $className): self
    {
        return new static("You have configured an invalid class `{$className}`.".
            'A valid class extends '.LanguageLine::class.'.');
    }
}

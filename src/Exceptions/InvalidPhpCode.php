<?php

namespace Spatie\Php7to5\Exceptions;

use Exception;

class InvalidPhpCode extends Exception
{
    public static function noValidLocationFoundToInsertClasses()
    {
        return new static('Could not find a location to save the converted anonymous classes');
    }
}

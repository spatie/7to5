<?php

namespace Spatie\Php7to5\Exceptions;

use Exception;

class InvalidParameter extends Exception
{
    public static function directoryDoesNotExist(string $directoryName) : self
    {
        return new static("Directory `{$directoryName}` does not exist");
    }

    public static function fileDoesNotExist(string $fileName) : self
    {
        return new static("File `{$fileName}` does not exist");
    }

    public static function directoryIsRequired() : self
    {
        return new static('A directory must be specified');
    }
}

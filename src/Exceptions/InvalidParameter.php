<?php

namespace Spatie\Php7to5\Exceptions;

use Exception;

class InvalidParameter extends Exception
{
    /**
     * @param string $directoryName
     * @return \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    public static function directoryDoesNotExist($directoryName)
    {
        return new static("Directory `{$directoryName}` does not exist");
    }

    /**
     * @param string $fileName
     * @return \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    public static function fileDoesNotExist($fileName)
    {
        return new static("File `{$fileName}` does not exist");
    }

    /**
     * @return \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    public static function directoryIsRequired()
    {
        return new static('A directory must be specified');
    }
}

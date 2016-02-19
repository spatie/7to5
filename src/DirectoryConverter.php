<?php

namespace Spatie\Php7to5;

use Spatie\Php7to5\Exceptions\InvalidParameter;

class DirectoryConverter
{
    /** @var string */
    protected $copyNonPhpFiles = true;

    public function __construct(string $sourceDirectory)
    {
        if (! file_exists($sourceDirectory)) {
            throw InvalidParameter::directoryDoesNotExist($sourceDirectory);
        }

        $this->sourceDirectory = $sourceDirectory;
    }

    public function alsoCopyNonPhpFiles() : self
    {
        $this->copyNonPhpFiles = true;

        return $this;
    }

    public function doNotCopyNonPhpFiles() : self
    {
        $this->copyNonPhpFiles = false;

        return $this;
    }

    public function savePhp5FilesTo(string $targetDirectory)
    {
        if (! file_exists($targetDirectory)) {
            throw InvalidParameter::directoryDoesNotExist($targetDirectory);
        }
    }
}
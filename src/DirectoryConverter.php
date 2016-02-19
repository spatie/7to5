<?php

namespace Spatie\Php7to5;

use FilesystemIterator;
use Spatie\Php7to5\Exceptions\InvalidParameter;

class DirectoryConverter
{
    /** @var string */
    protected $copyNonPhpFiles = true;

    public function __construct(string $sourceDirectory)
    {
        if (!file_exists($sourceDirectory)) {
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

    public function savePhp5FilesTo(string $destinationDirectory)
    {
        if ($destinationDirectory === '') {
            throw InvalidParameter::directoryIsRequired();
        }

        $this->copyDirectory($this->sourceDirectory, $destinationDirectory);
    }

    protected function copyDirectory(string $sourceDirectory, string $destinationDirectory)
    {
        if (!is_dir($destinationDirectory)) {
            mkdir($destinationDirectory);
        }

        $items = new FilesystemIterator($sourceDirectory, FilesystemIterator::SKIP_DOTS);

        foreach ($items as $item) {
            $target = $destinationDirectory.'/'.$item->getBasename();

            if ($item->isDir()) {
                $sourceDirectory = $item->getPathname();

                $this->copyDirectory($sourceDirectory, $target);
            } else {
                copy($item->getPathname(), $target);

                if (strtolower(pathinfo($target, PATHINFO_EXTENSION)) === 'php') {
                    $this->convertToPhp5($target);
                }
            }
        }
    }

    protected function convertToPhp5(string $filePath)
    {
        $converter = new Converter($filePath);

        $converter->saveAsPhp5($filePath);
    }
}

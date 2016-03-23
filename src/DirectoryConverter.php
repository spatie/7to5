<?php

namespace Spatie\Php7to5;

use FilesystemIterator;
use Spatie\Php7to5\Exceptions\InvalidParameter;
use Symfony\Component\Console\Output\OutputInterface;

class DirectoryConverter
{
    /** @var string */
    protected $copyNonPhpFiles = true;
    protected $logger;

    /**
     * DirectoryConverter constructor.
     *
     * @param string $sourceDirectory
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    public function __construct($sourceDirectory)
    {
        if (!file_exists($sourceDirectory)) {
            throw InvalidParameter::directoryDoesNotExist($sourceDirectory);
        }

        $this->sourceDirectory = $sourceDirectory;
    }

    public function setLogger(OutputInterface $output)
    {
        $this->logger = $output;
    }

    public function log($sourceItem, $target)
    {
        if (is_null($this->logger)) {
            return;
        }

        $this->logger->writeln("<comment>Converting {$sourceItem} to {$target}...</comment>");
    }

    /**
     * @return $this
     */
    public function alsoCopyNonPhpFiles()
    {
        $this->copyNonPhpFiles = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function doNotCopyNonPhpFiles()
    {
        $this->copyNonPhpFiles = false;

        return $this;
    }

    /**
     * @param string $destinationDirectory
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    public function savePhp5FilesTo($destinationDirectory)
    {
        if ($destinationDirectory === '') {
            throw InvalidParameter::directoryIsRequired();
        }

        $this->copyDirectory($this->sourceDirectory, $destinationDirectory);
    }

    /**
     * @param string $sourceDirectory
     * @param string $destinationDirectory
     */
    protected function copyDirectory($sourceDirectory, $destinationDirectory)
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
                if ($this->isPhpFile($target) || $this->copyNonPhpFiles) {
                    copy($item->getPathname(), $target);

                    $this->log($item->getBasename(), $target);

                    if (strtolower(pathinfo($target, PATHINFO_EXTENSION)) === 'php') {
                        $this->convertToPhp5($target);
                    }
                }
            }
        }
    }

    /**
     * @param string $filePath
     */
    protected function convertToPhp5($filePath)
    {
        $converter = new Converter($filePath);

        $converter->saveAsPhp5($filePath);
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    protected function isPhpFile($filePath)
    {
        return strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'php';
    }
}

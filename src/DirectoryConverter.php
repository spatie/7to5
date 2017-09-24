<?php

namespace Spatie\Php7to5;

use Spatie\Php7to5\Exceptions\InvalidParameter;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class DirectoryConverter
{
    /** @var string */
    protected $copyNonPhpFiles = true;
    /** @var string[] */
    protected $extensions;
    /** @var null|string[] */
    protected $excludes;
    protected $logger;

    /**
     * DirectoryConverter constructor.
     *
     * @param string $sourceDirectory
     * @param string[] $extensions
     * @param string[]|null $excludes
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    public function __construct($sourceDirectory, array $extensions, array $excludes = null)
    {
        if (!file_exists($sourceDirectory)) {
            throw InvalidParameter::directoryDoesNotExist($sourceDirectory);
        }

        $this->sourceDirectory = $sourceDirectory;
        $this->extensions = array_map('mb_strtolower', $extensions);
        $this->excludes = $excludes;
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
        $targetRealPath = realpath($target);

        $this->logger->writeln("<comment>Converting {$sourceItem} to {$targetRealPath}...</comment>");
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

        $finder = new Finder();
        $finder->in($sourceDirectory);
        if (!$this->copyNonPhpFiles) {
            foreach ($this->extensions as $extension) {
                $finder->name('*.'.$extension);
            }
        }
        if ($this->excludes) {
            foreach ($this->excludes as $exclude) {
                $finder->notPath($exclude);
            }
        }

        foreach ($finder as $item) {
            $target = $destinationDirectory.'/'.$item->getRelativePathname();

            if ($item->isFile()) {
                $isPhpFile = $this->isPhpFile($target);
                if ($isPhpFile || $this->copyNonPhpFiles) {
                    $targetDir = dirname($target);
                    if ($targetDir && !is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    copy($item->getRealPath(), $target);

                    $this->log($item->getRelativePath(), $target);

                    if ($isPhpFile) {
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
        return in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), $this->extensions, true);
    }
}

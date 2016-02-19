<?php

namespace Spatie\Php7to5\Test;

use Spatie\Php7to5\DirectoryConverter;

class DirectoryConverterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->initializeTempDirectory();
    }
    
    public function it_can_copy_an_entire_directory()
    {
        $directoryConverter = new DirectoryConverter($this->getSourceDirectory());

        $directoryConverter->savePhp5FilesTo($this->getTempDirectory());
    }

    public function initializeTempDirectory()
    {
        if (file_exists($this->getTempDirectory())) {
            unlink($this->getTempDirectory());
        }

        mkdir($this->getTempDirectory());

        $this->addGitignoreTo($this->getTempDirectory());
    }

    public function addGitignoreTo($directory)
    {
        $fileName = "{$directory}/.gitignore";

        $fileContents = '*'.PHP_EOL.'!.gitignore';

        file_put_contents($fileName, $fileContents);
    }

    public function getTempDirectory() : string
    {
        return __DIR__.'/stubs/temp';
    }

    public function getSourceDirectory() : string
    {
        return __DIR__ . '/stubs/directoryConverter';
    }
}

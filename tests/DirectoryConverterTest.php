<?php

namespace Spatie\Php7to5\Test;

use PHPUnit\Framework\TestCase;
use Illuminate\Filesystem\Filesystem;
use Spatie\Php7to5\DirectoryConverter;

class DirectoryConverterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->initializeTempDirectory();
    }

    /** @test */
    public function it_can_copy_and_convert_an_entire_directory()
    {
        $directoryConverter = new DirectoryConverter($this->getSourceDirectory(), ['php']);

        $directoryConverter->savePhp5FilesTo($this->getTempDirectory());

        $this->assertTempFileExists([
            'sourceDirectory/file1.php',
            'sourceDirectory/file2.php',
            'sourceDirectory/file3.txt',
            'sourceDirectory/directory1/file1.php',
            'sourceDirectory/directory1/file2.php',
            'sourceDirectory/directory1/file3.txt',
        ]);

        $this->assertAllPhpFilesWereConverted($this->getTempDirectory());
    }

    /** @test */
    public function it_can_copy_and_convert_an_entire_directory_filtering_on_php_files()
    {
        $directoryConverter = new DirectoryConverter($this->getSourceDirectory(), ['php']);

        $directoryConverter
            ->doNotCopyNonPhpFiles()
            ->savePhp5FilesTo($this->getTempDirectory());

        $this->assertTempFileExists([
            'sourceDirectory/file1.php',
            'sourceDirectory/file2.php',
            'sourceDirectory/directory1/file1.php',
            'sourceDirectory/directory1/file2.php',
        ]);

        $this->assertTempFileNotExists([
            'sourceDirectory/file3.txt',
            'sourceDirectory/directory1/file3.txt',
            'sourceDirectory/directory1/file4.phtml',
        ]);

        $this->assertAllPhpFilesWereConverted($this->getTempDirectory());
    }

    /** @test */
    public function it_can_copy_and_convert_an_entire_directory_filtering_on_custom_php_files()
    {
        $directoryConverter = new DirectoryConverter($this->getSourceDirectory(), ['php', 'phtml']);

        $directoryConverter
            ->doNotCopyNonPhpFiles()
            ->savePhp5FilesTo($this->getTempDirectory());

        $this->assertTempFileExists([
            'sourceDirectory/file1.php',
            'sourceDirectory/file2.php',
            'sourceDirectory/directory1/file1.php',
            'sourceDirectory/directory1/file2.php',
            'sourceDirectory/directory1/file4.phtml',
        ]);

        $this->assertTempFileNotExists([
            'sourceDirectory/file3.txt',
            'sourceDirectory/directory1/file3.txt',
        ]);

        $this->assertAllPhpFilesWereConverted($this->getTempDirectory());
    }

    /** @test */
    public function it_can_copy_and_convert_an_entire_directory_with_exclude_directory()
    {
        $directoryConverter = new DirectoryConverter($this->getSourceDirectory(), ['php'], ['sourceDirectory/directory1']);

        $directoryConverter
            ->doNotCopyNonPhpFiles()
            ->savePhp5FilesTo($this->getTempDirectory());

        $this->assertTempFileExists([
            'sourceDirectory/file1.php',
            'sourceDirectory/file2.php',
        ]);

        $this->assertTempFileNotExists([
            'sourceDirectory/file3.txt',
            'sourceDirectory/directory1/file1.php',
            'sourceDirectory/directory1/file2.php',
            'sourceDirectory/directory1/file3.txt',
            'sourceDirectory/directory1/file4.phtml',
        ]);

        $this->assertAllPhpFilesWereConverted($this->getTempDirectory());
    }

    /** @test */
    public function it_can_copy_and_convert_an_entire_directory_with_exclude_file()
    {
        $directoryConverter = new DirectoryConverter($this->getSourceDirectory(), ['php'], ['sourceDirectory/directory1/file1.php']);

        $directoryConverter
            ->doNotCopyNonPhpFiles()
            ->savePhp5FilesTo($this->getTempDirectory());

        $this->assertTempFileExists([
            'sourceDirectory/file1.php',
            'sourceDirectory/file2.php',
            'sourceDirectory/directory1/file2.php',
        ]);

        $this->assertTempFileNotExists([
            'sourceDirectory/file3.txt',
            'sourceDirectory/directory1/file1.php',
            'sourceDirectory/directory1/file3.txt',
            'sourceDirectory/directory1/file4.phtml',
        ]);

        $this->assertAllPhpFilesWereConverted($this->getTempDirectory());
    }

    /** @test */
    public function it_can_clean_destination_directory_before_conversion()
    {
        # Convert all php files including sub-directories
        $directoryConverter1 = new DirectoryConverter(
            $this->getSourceDirectory(),
            ['php']
        );
        $directoryConverter1->savePhp5FilesTo($this->getTempDirectory());

        $directoryConverter2 = new DirectoryConverter(
            $this->getSourceDirectory(),
            ['php'],
            ['sourceDirectory/directory1']
        );
        $directoryConverter2
            ->cleanDestinationDirectory()
            ->savePhp5FilesTo($this->getTempDirectory());

        $this->assertTempFileExists([
            'sourceDirectory/file1.php',
            'sourceDirectory/file2.php',
            'sourceDirectory/file3.txt'
        ]);

        $this->assertTempFileNotExists([
            'sourceDirectory/directory1/file1.php',
            'sourceDirectory/directory1/file2.php',
            'sourceDirectory/directory1/file3.txt',
            'sourceDirectory/directory1/file4.phtml'
        ]);

        $this->assertAllPhpFilesWereConverted($this->getTempDirectory());

        $this->addGitignoreTo($this->getTempDirectory());
    }

    public function initializeTempDirectory()
    {
        (new Filesystem())->deleteDirectory($this->getTempDirectory());

        mkdir($this->getTempDirectory());

        $this->addGitignoreTo($this->getTempDirectory());
    }

    /**
     * @param string $directory
     */
    public function addGitignoreTo($directory)
    {
        $fileName = "{$directory}/.gitignore";

        $fileContents = '*'.PHP_EOL.'!.gitignore';

        file_put_contents($fileName, $fileContents);
    }

    /**
     * @return string
     */
    public function getTempDirectory()
    {
        return __DIR__.'/stubs/temp';
    }

    /**
     * @return string
     */
    public function getSourceDirectory()
    {
        return __DIR__.'/stubs/directoryConverter';
    }

    /**
     * @param array $files
     */
    protected function assertTempFileExists(array $files)
    {
        foreach ($files as $file) {
            $this->assertFileExists("{$this->getTempDirectory()}/{$file}");
        }
    }

    /**
     * @param array $files
     */
    protected function assertTempFileNotExists(array $files)
    {
        foreach ($files as $file) {
            $this->assertFileNotExists("{$this->getTempDirectory()}/{$file}");
        }
    }

    /**
     * @param string $directory
     */
    protected function assertAllPhpFilesWereConverted($directory)
    {
        $convertedPhpFileContents = file_get_contents(__DIR__.'/stubs/converter/it-can-remove-declarations-statement/php5.php');

        $allFiles = (new Filesystem())->allFiles($directory);

        foreach ($allFiles as $file) {
            if ($file->getExtension() == 'php') {
                $this->assertSame(trim($convertedPhpFileContents), file_get_contents($file->getRealPath()));
            }
        }
    }
}

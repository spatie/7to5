<?php

namespace Spatie\Php7to5\Test;

use Symfony\Component\Process\Process;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ConsoleCommandTest.
 */
class ConsoleCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $inputFile;
    protected $outputFile;
    protected $sourceDirectory;
    protected $destinationDirectory;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->cleanDestinationDirectory();
        $this->inputFile = $this->getStubsDirectory().'/converter/it-can-remove-scalar-type-hints/php7.php';
        $this->outputFile = $this->getConsoleCommand().'/php5.php';
        $this->sourceDirectory = $this->getStubsDirectory().'/directoryConverter/sourceDirectory';
        $this->destinationDirectory = $this->getConsoleCommand().'/destinationDirectory';
    }

    /** @test */
    public function it_can_convert_php7file_to_php5file_to_a_wanted_directory()
    {
        $command = $this->getCommand($this->inputFile, $this->outputFile, '--overwrite');
        $this->runCommand($command);

        $this->assertFileExists($this->outputFile);
    }

    /** @test */
    public function it_can_convert_all_php_files_from_a_given_directory()
    {
        $destinationDirectory = $this->destinationDirectory;
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, '--overwrite');
        $this->runCommand($command);

        $this->assertTempFileNotExists([
            $destinationDirectory.'/file3.txt',
            $destinationDirectory.'/directory1/file3.txt',
            $destinationDirectory.'/directory1/file4.phtml',
        ]);

        $this->assertTempFileExists([
            $destinationDirectory.'/file1.php',
            $destinationDirectory.'/file2.php',
            $destinationDirectory.'/directory1/file1.php',
            $destinationDirectory.'/directory1/file2.php',
        ]);
    }

    /** @test */
    public function it_can_convert_all_php_files_from_a_given_directory_with_additional_extension()
    {
        $destinationDirectory = $this->destinationDirectory;
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, '--overwrite --extension=php --extension=phtml');
        $this->runCommand($command);

        $this->assertTempFileNotExists([
            $destinationDirectory.'/file3.txt',
            $destinationDirectory.'/directory1/file3.txt',
        ]);

        $this->assertTempFileExists([
            $destinationDirectory.'/file1.php',
            $destinationDirectory.'/file2.php',
            $destinationDirectory.'/directory1/file1.php',
            $destinationDirectory.'/directory1/file2.php',
            $destinationDirectory.'/directory1/file4.phtml',
        ]);
    }

    /** @test */
    public function it_can_convert_all_php_files_from_a_given_directory_with_exclude_directory()
    {
        $destinationDirectory = $this->destinationDirectory;
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, '--overwrite --exclude=directory1');
        $this->runCommand($command);

        $this->assertTempFileNotExists([
            $destinationDirectory.'/file3.txt',
            $destinationDirectory.'/directory1/file3.txt',
            $destinationDirectory.'/directory1/file1.php',
            $destinationDirectory.'/directory1/file2.php',
            $destinationDirectory.'/directory1/file4.phtml',
        ]);

        $this->assertTempFileExists([
            $destinationDirectory.'/file1.php',
            $destinationDirectory.'/file2.php',
        ]);
    }

    /** @test */
    public function it_can_convert_all_php_files_from_a_given_directory_with_exclude_file()
    {
        $destinationDirectory = $this->destinationDirectory;
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, '--overwrite --exclude=directory1/file2.php');
        $this->runCommand($command);

        $this->assertTempFileNotExists([
            $destinationDirectory.'/file3.txt',
            $destinationDirectory.'/directory1/file3.txt',
            $destinationDirectory.'/directory1/file2.php',
            $destinationDirectory.'/directory1/file4.phtml',
        ]);

        $this->assertTempFileExists([
            $destinationDirectory.'/file1.php',
            $destinationDirectory.'/file2.php',
            $destinationDirectory.'/directory1/file1.php',
        ]);
    }

    /** @test */
    public function it_can_convert_all_files_from_a_given_directory_if_option_copy_all_is_given()
    {
        $destinationDirectory = $this->destinationDirectory;
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, '--copy-all --overwrite');
        $this->runCommand($command);

        $this->assertTempFileExists([
            $destinationDirectory.'/file1.php',
            $destinationDirectory.'/file2.php',
            $destinationDirectory.'/file3.txt',
            $destinationDirectory.'/directory1/file1.php',
            $destinationDirectory.'/directory1/file2.php',
            $destinationDirectory.'/directory1/file3.txt',
            $destinationDirectory.'/directory1/file4.phtml',
        ]);
    }

    /** @test */
    public function it_throws_an_exception_if_a_file_exist_and_overwriting_is_not_allowed()
    {
        $command = $this->getCommand($this->inputFile, $this->outputFile, null);

        $this->assertThrowsException($command, '[Spatie\Php7to5\Exceptions\InvalidParameter]');
    }

    /** @test */
    public function it_throws_an_exception_if_a_directory_exist_and_overwriting_is_not_allowed()
    {
        $destinationDirectory = $this->getConsoleCommand().'/directoryExist';
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, null);

        $this->assertThrowsException($command, '[Spatie\Php7to5\Exceptions\InvalidParameter]');
    }

    /** @test */
    public function it_throws_an_exception_if_a_give_destination_directory_is_in_a_source_directory()
    {
        $sourceDirectory = $this->sourceDirectory;
        $destinationDirectory = "{$sourceDirectory}/php5";
        $command = $this->getCommand($sourceDirectory, $destinationDirectory, null);

        $this->assertThrowsException($command, '[Spatie\Php7to5\Exceptions\InvalidParameter]');
    }
    /** @test */
    public function it_throws_an_exception_if_source_directory_does_not_exist()
    {
        $sourceDirectory = $this->getStubsDirectory().'/directoryConverter/sourceDir';
        $command = $this->getCommand($sourceDirectory, $this->destinationDirectory, '--overwrite');

        $this->assertThrowsException($command, '[Spatie\Php7to5\Exceptions\InvalidParameter]');
    }

    /** @test */
    public function it_throws_an_exception_if_destination_is_same_as_source_and_overwriting_is_not_allowed()
    {
        $command = $this->getCommand($this->destinationDirectory, $this->destinationDirectory);

        $this->assertThrowsException($command, '[Spatie\Php7to5\Exceptions\InvalidParameter]');
    }

    /** @test */
    public function it_throws_an_exception_if_source_file_does_not_exist()
    {
        $sourceFile = $this->getStubsDirectory().'/converter/it-can-remove-scalar-type-hints/php.php';
        $command = $this->getCommand($sourceFile, $this->destinationDirectory, '--overwrite');

        $this->assertThrowsException($command, '[Spatie\Php7to5\Exceptions\InvalidParameter]');
    }

    /**
     * @param $files
     */
    protected function assertTempFileNotExists($files)
    {
        collect($files)->each(function ($file) {
            $this->assertFileNotExists($file);
        });
    }

    /**
     * @param $files
     */
    protected function assertTempFileExists($files)
    {
        collect($files)->each(function ($file) {
            $this->assertFileExists($file);
        });
    }

    /**
     * @param $inputFile
     * @param $outputFile
     * @param $options
     *
     * @return string
     */
    protected function getCommand($inputFile, $outputFile, $options = null)
    {
        return "./php7to5 convert {$inputFile} {$outputFile} {$options}";
    }

    /**
     * @param $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function runCommand($command)
    {
        $process = new Process($command);
        $process->run();

        return $process;
    }

    /**
     * @return string
     */
    protected function getConsoleCommand()
    {
        return __DIR__.'/stubs/consoleCommand';
    }

    /**
     * @return string
     */
    protected function getStubsDirectory()
    {
        return __DIR__.'/stubs';
    }

    /**
     * @param $command
     * @param $exception
     */
    protected function assertThrowsException($command, $exception)
    {
        $process = $this->runCommand($command);

        $this->assertTrue(str_contains($process->getErrorOutput(), $exception));
    }

    private function cleanDestinationDirectory()
    {
        $destinationDirectory = $this->getConsoleCommand().'/destinationDirectory';
        $filesystem = new Filesystem();

        $filesystem->deleteDirectory($destinationDirectory);
    }
}

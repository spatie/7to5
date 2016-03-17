<?php

namespace Spatie\Php7to5\Test;

use Symfony\Component\Process\Process;
/**
 * Class ConsoleCommandTest
 *
 * @package \Spatie\Php7to5\Test
 */
class ConsoleCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $inputFile;
    protected $outputFile;
    protected $sourceDirectory;
    protected $destinationDirectory;

    protected function setUp()
    {
        parent::setUp();
        $this->inputFile = $this->getStubsDirectory(). '/converter/it-can-remove-scalar-type-hints/php7.php';
        $this->outputFile = $this->getConsoleCommand().'/php5.php';
        $this->sourceDirectory = $this->getStubsDirectory(). '/directoryConverter/sourceDirectory';
        $this->destinationDirectory = $this->getConsoleCommand() . '/destinationDirectory';
    }

    /** @test */
    public function it_can_convert_php7file_to_php5file_to_a_wanted_directory()
    {
        $command = $this->getCommand($this->inputFile, $this->outputFile,'--overwrite');
        $this->runCommand($command);

        $this->assertFileExists($this->outputFile);
    }

    /** @test */
    public function it_can_convert_php_files_from_a_given_directory()
    {
        $destinationDirectory = $this->destinationDirectory;
        $command = $this->getCommand($this->sourceDirectory, $destinationDirectory, '--overwrite');
        $this->runCommand($command);

        $this->assertTempFileExists([
            $destinationDirectory.'/file1.php',
            $destinationDirectory.'/file2.php',
            $destinationDirectory.'/directory1/file1.php',
            $destinationDirectory.'/directory1/file2.php',

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

        ]);
    }


    /** @test */
    public function it_throws_an_exception_if_a_file_exist_and_option_overwrite_is_not_given()
    {
        $command = $this->getCommand($this->inputFile, $this->outputFile, null);
        $process = $this->runCommand($command);

        $this->assertTrue(str_contains($process->getErrorOutput(), '[Spatie\Php7to5\Exceptions\InvalidParameter]'));

    }

    /** @test */
    public function it_throws_an_exception_if_a_directory_exist_and_option_overwrite_is_not_given()
    {
        $command = $this->getCommand($this->sourceDirectory, $this->destinationDirectory, null);
        $process = $this->runCommand($command);

        $this->assertTrue(str_contains($process->getErrorOutput(), '[Spatie\Php7to5\Exceptions\InvalidParameter]'));

    }

    /** @test */
    public function it_throws_an_exception_if_a_give_destination_directory_is_in_a_source_directory()
    {
        $sourceDirectory = $this->sourceDirectory;
        $destinationDirectory = "{$sourceDirectory}/php5";
        $command = $this->getCommand($sourceDirectory, $destinationDirectory, null);
        $process = $this->runCommand($command);

        $this->assertTrue(str_contains($process->getErrorOutput(), '[Spatie\Php7to5\Exceptions\InvalidParameter]'));

    }

    protected function assertTempFileExists(array $files)
    {
        collect($files)->each(function($file){
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
    protected function getCommand($inputFile, $outputFile, $options)
    {
        return "./php7to5 convert {$inputFile} {$outputFile} {$options}";
    }

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

}

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

    /** @test */
    public function it_can_convert_php7file_to_php5file_to_a_wanted_directory()
    {
        $files = $this->getFiles();
        $command = $this->getCommand($files, null, '--overwrite');
        $this->runCommand($command);

        $this->assertFileExists($files['destination']);
    }

    /** @test */
    public function it_can_convert_php_files_from_a_given_directory()
    {

        $directories = $this->getDirectories();
        $command = $this->getCommand($directories, null, '--overwrite');
        $this->runCommand($command);

        $this->assertTempFileExists([
            $directories['destination'].'/file1.php',
            $directories['destination'].'/file2.php',
            $directories['destination'].'/directory1/file1.php',
            $directories['destination'].'/directory1/file2.php',

        ]);
    }

    /** @test */
    public function it_can_convert_all_files_from_a_given_directory_if_option_copy_all_is_given()
    {
        $directories = $this->getDirectories();
        $command = $this->getCommand($directories, '--copy-all', '--overwrite');
        $this->runCommand($command);

        $this->assertTempFileExists([
            $directories['destination'].'/file1.php',
            $directories['destination'].'/file2.php',
            $directories['destination'].'/file3.txt',
            $directories['destination'].'/directory1/file1.php',
            $directories['destination'].'/directory1/file2.php',
            $directories['destination'].'/directory1/file3.txt',

        ]);
    }


    /** @test */
    public function it_throws_an_exception_if_a_file_exist_and_option_overwrite_is_not_given()
    {
        $files = $this->getFiles();
        $command = $this->getCommand($files, null, null);
        $process = $this->runCommand($command);
        $exception = 'A directory with a given name already exists. if you want to overwrite it, you must specify that as an option.';

        $this->assertTrue(str_contains($process->getErrorOutput(), $exception));

    }

    /** @test */
    public function it_throws_an_exception_if_a_directory_exist_and_option_overwrite_is_not_given()
    {
        $directories = $this->getDirectories();
        $command = $this->getCommand($directories, null, null);
        $process = $this->runCommand($command);
        $exception = 'A directory with a given name already exists. if you want to overwrite it, you must specify that as an option.';

        $this->assertTrue(str_contains($process->getErrorOutput(), $exception));

    }

    /** @test */
    public function it_throws_an_exception_if_a_give_destination_directory_is_in_a_source_directory()
    {
        $directories = $this->getDirectories();
        $input = ['source' => $directories['source'], 'destination' => "{$directories['source']}/php5"];
        $command = $this->getCommand($input, null, null);
        $process = $this->runCommand($command);
        $exception = "A destination directory can't be inside of a source directory!";

        $this->assertTrue(str_contains($process->getErrorOutput(), $exception));

    }

    protected function getCommand($input, $copy_all = null, $overwrite = null)
    {
        $command = "./php7to5 convert {$input['source']} {$input['destination']} {$copy_all} {$overwrite}";

        return $command;

    }

    protected function runCommand($command)
    {
        $process = new Process($command);
        $process->run();

        return $process;
    }

    protected function assertTempFileExists(array $files)
    {
        foreach($files as $file) {
            $this->assertFileExists($file);
        }
    }

    protected function getConsoleCommand()
    {
        return __DIR__.'/stubs/consoleCommand';
    }

    protected function getStubsDirectory()
    {
        return __DIR__.'/stubs';
    }

    protected function getFiles()
    {
        $source = $this->getStubsDirectory(). '/converter/it-can-remove-scalar-type-hints/php7.php';
        $destination = $this->getConsoleCommand().'/php5.php';
        return ['source' => $source, 'destination' =>$destination];
    }

    protected function getDirectories()
    {
        $source =  $this->getStubsDirectory(). '/directoryConverter/sourceDirectory';
        $destination = $this->getConsoleCommand().'/destinationDirectory';
        return ['source' => $source, 'destination' =>$destination];
    }

}

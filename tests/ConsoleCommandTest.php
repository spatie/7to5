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
        $source =  $this->getStubsDirectory(). '/directoryConverter/sourceDirectory';
        $destination = $this->getConsoleCommand().'/destinationDirectory';

        $command = $this->getCommand(['source' => $source, 'destination' =>$destination], null, '--overwrite');
        $this->runCommand($command);

        $this->assertTempFileExists([
            $destination.'/file1.php',
            $destination.'/file2.php',
            $destination.'/directory1/file1.php',
            $destination.'/directory1/file2.php',

        ]);
    }


    /** @test */
    public function it_doesnt_overwrite_file_if_option_overwrite_is_not_given()
    {
        $files = $this->getFiles();
        $command = $this->getCommand($files, null, null);
        $process = $this->runCommand($command);
        dd($process->getExitCodeText());
    }

    /** @test */
    public function it_doesnt_overwrite_directory_if_option_overwrite_is_not_given()
    {

    }

    protected function getCommand($input, $copy_all = null, $overwrite = null)
    {
        $command = './php7to5 convert '.$input['source'].' '.$input['destination'].' '.$overwrite;

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
}

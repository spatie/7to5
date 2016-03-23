<?php

namespace Spatie\Php7to5\Console;

use Spatie\Php7to5\Converter;
use Spatie\Php7to5\DirectoryConverter;
use Spatie\Php7to5\Exceptions\InvalidParameter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ConvertCommand extends Command
{
    protected function configure()
    {
        $this->setName('convert')
            ->setDescription('Convert PHP 7 code to PHP 5 code')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'A PHP 7 file or a directory containing PHP 7 files'
            )
            ->addArgument(
                'destination',
                InputArgument::REQUIRED,
                'The file or path where the PHP 5 code should be saved'
            )
            ->addOption(
                'copy-all',
                null,
                InputOption::VALUE_NONE,
                'If set, will copy all files in a directory, not only php'
            )
            ->addOption(
                'overwrite',
                null,
                InputOption::VALUE_NONE,
                'If set, will overwrite existing destination file or directory'
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start converting {$input->getArgument('source')}");

        $source = $input->getArgument('source');

        if (!file_exists($source)) {
            throw InvalidParameter::sourceDoesNotExist($source);
        }

        if (is_file($source)) {
            $this->convertFile($input);
        }
        if (is_dir($source)) {
            $this->convertPHPFilesInDirectory($input, $output);
        }
        $output->writeln('<info>All done!</info>');

        return 0;
    }

    protected function convertFile(InputInterface $input)
    {
        $converter = new Converter($input->getArgument('source'));
        $destination = $input->getArgument('destination');

        if (file_exists($destination) && !$input->getOption('overwrite')) {
            throw InvalidParameter::destinationExist();
        }
        $converter->saveAsPhp5($destination);
    }

    protected function convertPHPFilesInDirectory(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');
        $converter = new DirectoryConverter($source);

        $this->isDestinationASourceDirectory($source, $destination);
        $this->isDestinationDifferentThanSource($source, $destination);

        if (!$input->getOption('copy-all')) {
            $converter->doNotCopyNonPhpFiles();
        }

        if (file_exists($destination) && !$input->getOption('overwrite')) {
            throw InvalidParameter::destinationExist();
        }

        $converter->setLogger($output);
        $converter->savePhp5FilesTo($destination);
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    protected function isDestinationASourceDirectory($source, $destination)
    {
        $this->isEqual($source, $destination);
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    protected function isDestinationDifferentThanSource($source, $destination)
    {
        $path_parts = pathinfo($destination);
        $this->isEqual($source, $path_parts['dirname']);
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @throws \Spatie\Php7to5\Exceptions\InvalidParameter
     */
    protected function isEqual($source, $destination)
    {
        if (!ends_with($destination, DIRECTORY_SEPARATOR)) {
            $destination = $destination.DIRECTORY_SEPARATOR;
        }
        if (!ends_with($source, DIRECTORY_SEPARATOR)) {
            $source = $source.DIRECTORY_SEPARATOR;
        }

        if ($destination === $source) {
            throw InvalidParameter::destinationDirectoryIsSource();
        }
    }
}

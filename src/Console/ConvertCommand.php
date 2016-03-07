<?php

namespace Spatie\Php7to5\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start converting {$input->getArgument('source')}");
        $output->writeln('');

        $output->writeln("All done!");
        $output->writeln('');

        return 0;
    }
}

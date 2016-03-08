<?php

namespace Spatie\Php7to5\Console;

use Spatie\Php7to5\Converter;
use Spatie\Php7to5\DirectoryConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

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

        if(is_file($input->getArgument('source'))){
            $this->convertFile($input, $output);
        }

        if(is_dir($input->getArgument('source'))){

            $this->convertPHPFilesInDirectory($input, $output);
        }

        // DIR
        // only php or all files

        $output->writeln("<info>All done!</info>");
        $output->writeln('');

        return 0;
    }

    protected function convertFile($input, $output)
    {
        $converter = new Converter($input->getArgument('source'));

        if(file_exists($input->getArgument('destination'))){
//            $this->getOverride('file', $output, $converter->savePhp5FilesTo($input->getArgument('destination')));
        }
        else $converter->saveAsPhp5($input->getArgument('destination'));


    }

    protected function convertPHPFilesInDirectory($input, $output)
    {
        $converter = new DirectoryConverter($input->getArgument('source'));
        $destination = $input->getArgument('destination');
        $path_parts = pathinfo($destination);
        if($path_parts['dirname'] === $input->getArgument('source')){
            $this->makeFormatter($output, 'error');
            $output->writeln('<error>Destination path must outside the source directory!</error>');
            $output->writeln('<error>Aborting.</error>');
            exit();
        }

        $this->makeFormatter($output, 'question');
        $output->writeln('<question>Do you also want copy not php files to the destination directory? ( y|n ) </question>');

        if($this->getAnswer() === 'n'){
            $converter->doNotCopyNonPhpFiles();
        }

        if(!file_exists($input->getArgument('destination'))){

            $converter->savePhp5FilesTo($destination);
            $output->writeln("<info>All done!</info>");
            exit();
        }

        $output->writeln('<question>Destination directory with your given name already exists. Do you want to override it? ( y|n ) </question>');

        if($this->getAnswer() !== 'y'){
            $output->writeln('<comment>Aborting.</comment>');
            exit();

        }
        $output->writeln('<info>Overriding.</info>');
        $converter->savePhp5FilesTo($destination);
    }


    protected function getAnswer()
    {
        readline();
        $info = readline_info();
        return $info['line_buffer'];

    }

    protected function makeFormatter($output, $case)
    {
        switch($case){
            case 'error': $formatter = new OutputFormatterStyle('red');
                break;
            case 'question': $formatter = new OutputFormatterStyle('cyan');
                break;

        }

        return $output->getFormatter()->setStyle($case, $formatter);

    }

}

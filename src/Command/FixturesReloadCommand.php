<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixturesReloadCommand extends Command
{
    protected static $defaultName = 'app:fixtures-reload';
    protected static $defaultDescription = 'Drop/Create Database and load Fixtures';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->setHelp('This command allows you to load dummy data by recreating database and loading fixtures...')
            // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:drop', "--force" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));


        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:create', "--if-not-exists" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********         Updating Schema         *********',
            '===================================================',
            '',
        ]);

        //Create de Schema
        $options = array('command' => 'doctrine:schema:update', "--force" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        //Loading Fixtures
        $options = array('command' => 'doctrine:fixtures:load', "--group" => ["FakeFixtures"], "--no-interaction" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        return 0;
    }
}

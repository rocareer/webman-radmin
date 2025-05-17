<?php

namespace plugin\radmin\app\command;

use DI\Container;
use DI\ContainerBuilder;
use plugin\radmin\support\member\admin\AdminModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use function DI\create;

#[AsCommand('test:con', 'test con')]
class TestCon extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $admin=AdminModel::select();


        return self::SUCCESS;
    }

}

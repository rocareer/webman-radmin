<?php

namespace plugin\radmin\app\command;

use DI\Container;
use DI\ContainerBuilder;
use plugin\radmin\support\member\admin\AdminModel;
use plugin\radmin\support\Test;
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
       // var_dump((new \DI\Container)->getKnownEntryNames());
       // \support\Container::instance('radmin');
       //  require_once base_path(). '/plugin/radmin/config/container.php';
        // \support\Container::get('test1');

        $admin=AdminModel::select();
        var_dump($admin);

        return self::SUCCESS;
    }

}

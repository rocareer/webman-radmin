<?php

namespace Radmin\command;

use Radmin\Command;
use Radmin\util\FileUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('radmin:export', 'radmin export')]
class RadminExport extends Command
{
    protected mixed $force;
    private string  $plugin_path;
    private string  $plugin_vendor;
    /**
     * @var array|string[]
     */
    private array $paths;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
        $this->addOption('force','f', InputOption::VALUE_NONE, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input=$input;
        $this->output=$output;
        $this->initializeStats();

        $this->plugin_path=base_path().'/plugin/radmin';
        $this->plugin_vendor= base_path() . DIRECTORY_SEPARATOR .'vendor/rocareer/webman-radmin';

        $this->paths=[
            'web'=>'web',
            'plugin/radmin'=>'plugin/radmin',
            'config/plugin/rocareer/webman-radmin'=>'config/plugin/rocareer/webman-radmin',
        ];
        $name = $input->getArgument('name');
        $this->force = $input->getOption('force');
        $this->sync();
        $output->writeln('Hello radmin:export');
        return self::SUCCESS;
    }

    public function sync()
    {
        foreach ($this->paths as $source=>$target) {
            $source=base_path() . DIRECTORY_SEPARATOR .$source;
            $target=$this->plugin_vendor.DIRECTORY_SEPARATOR.$target;
            FileUtil::syncDir($source, $target,['*node_modules*']);
        }

    }

}

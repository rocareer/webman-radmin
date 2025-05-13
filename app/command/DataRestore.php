<?php

namespace app\command;

use think\facade\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class DataRestore extends Command
{
    protected static $defaultName = 'data:restore';
    protected static $defaultDescription = 'Restore database tables from SQL file';

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Path to SQL backup file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $output->writeln("<error>File not found: {$file}</error>");
            return self::FAILURE;
        }

        $sql = file_get_contents($file);
        $queries = $this->splitSql($sql);

        Db::startTrans();
        try {
            $total = count($queries);
            $output->writeln("Starting restore from {$file}");
            $output->writeln("Found {$total} SQL statements to execute");

            foreach ($queries as $i => $query) {
                $output->writeln("Executing statement ".($i+1)."/{$total}");
                Db::execute($query);
            }

            Db::commit();
            $output->writeln("<info>Restore completed successfully</info>");
            return self::SUCCESS;

        } catch (\Exception $e) {
            Db::rollback();
            $output->writeln("<error>Restore failed: ".$e->getMessage()."</error>");
            return self::FAILURE;
        }
    }

    protected function splitSql(string $sql): array
    {
        // 分割SQL文件中的多个语句
        $queries = [];
        $current = '';

        $lines = explode("\n", $sql);
        foreach ($lines as $line) {
            if (trim($line) === '' || substr(trim($line), 0, 2) === '--') {
                continue;
            }

            $current .= $line;
            if (substr(rtrim($current), -1) === ';') {
                $queries[] = $current;
                $current = '';
            }
        }

        return $queries;
    }
}

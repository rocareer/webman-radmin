<?php
/**
 * File:        Command.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/17 23:13
 * Description: 命令行工具基类
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace plugin\radmin\support;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class Command extends BaseCommand
{
    protected float $startTime;
    protected int   $startMemory;
    protected float $peakMemory = 0;
    protected OutputInterface $output;
    protected InputInterface  $input;

    /**
     * 初始化计时器和内存统计
     */
    protected function initializeStats(): void
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }

    /**
     * 更新内存使用峰值
     */
    protected function updateMemoryUsage(): void
    {
        $current = memory_get_usage(true) / 1024 / 1024;
        $this->peakMemory = max($this->peakMemory, $current);
    }

    /**
     * 获取当前内存使用(MB)
     */
    protected function getMemoryUsage(): float
    {
        return round((memory_get_usage() - $this->startMemory) / 1024 / 1024, 2);
    }

    /**
     * 获取峰值内存使用(MB)
     */
    protected function getPeakMemoryUsage(): float
    {
        return round($this->peakMemory, 2);
    }

    /**
     * 获取执行时间(秒)
     */
    protected function getExecutionTime(): float
    {
        return round(microtime(true) - $this->startTime, 2);
    }

    /**
     * 创建表格输出
     */
    protected function createTable(OutputInterface $output): Table
    {
        return new Table($output);
    }

    /**
     * 创建进度条
     */
    protected function createProgressBar(OutputInterface $output, int $max = 0): ProgressBar
    {
        $progress = new ProgressBar($output, $max);
        $progress->setFormat("
 %current%/%max% [%bar%] %percent:3s%%
 耗时: %elapsed:6s% 预计: %estimated:-6s% 内存: %memory:6s%
 状态: %message%
");
        $progress->setBarWidth(50);
        $progress->setRedrawFrequency(1);
        return $progress;
    }

    /**
     * 格式化输出对齐
     */
    protected function formatOutput(string $message, string $data, int $totalWidth): string
    {
        $messageWidth = mb_strwidth($message, 'UTF-8');
        $dataWidth = mb_strwidth($data, 'UTF-8');
        $padding = $totalWidth - $messageWidth - $dataWidth;
        return $message . str_repeat(' ', $padding) . $data;
    }


    /**
     * 输出头部信息
     * @param             $name
     * @param string|null $title
     * @param string|null $version
     * @param mixed|null  $other
     * @return   void
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/17 23:54
     */
    protected function outputHeader($name,?string $title=null,?string $version=null,mixed $other=null): void
    {
        if ($title){
            $this->output->writeln("$title");
            usleep(1000000);
        }
        if ($name){
            $this->output->writeln("Name    $name   Starting ...");
            usleep(1000000);
        }
        if ($version){
            $this->output->writeln("Version $version ...");
            usleep(1000000);
        }
        if ($other){
            if (is_array($other)){
                foreach ($other as $tltle => $content){
                    $this->output->writeln("$tltle: $content");
                }
            }
            if (is_string($other)){
                $this->output->writeln("$other");
            }
        }
    }
    /**
     * 确认提示
     */
    protected function confirm(InputInterface $input, OutputInterface $output, string $question, bool $default = false): bool
    {
        $helper = $this->getHelper('question');
        return $helper->ask(
            $input, 
            $output, 
            new ConfirmationQuestion($question, $default)
        );
    }

    /**
     * 输出带颜色的消息
     */
    protected function coloredMessage(string $message, string $color): string
    {
        $colors = [
            'black' => '30',
            'red' => '31',
            'green' => '32',
            'yellow' => '33',
            'blue' => '34',
            'magenta' => '35',
            'cyan' => '36',
            'white' => '37',
        ];
        
        return isset($colors[$color]) 
            ? sprintf("\033[%sm%s\033[0m", $colors[$color], $message)
            : $message;
    }

    /**
     * 输出统计表格
     */
    protected function outputStatisticsTable(OutputInterface $output, array $stats): void
    {
        $table = $this->createTable($output);
        $table->setStyle('box-double');
        
        $rows = [];
        foreach ($stats as $label => $value) {
            $rows[] = [$label, $value];
        }
        
        $table->setRows($rows);
        $table->render();
    }
}
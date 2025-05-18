<?php


/**
 * File      SpiderService.php
 * Author    albert@rocareer.com
 * Time      2025-04-24 05:04:51
 * Describe  SpiderService.php
 */

namespace plugin\radmin\support\rocareer;

use  app\admin\model\spider\Process;
use plugin\radmin\app\admin\model\spider\Spider;

class SpiderService
{
    protected $spiderModel;
    protected $processModel;

    public function __construct()
    {
        $this->spiderModel = new Spider();
        $this->processModel = new Process();
    }

    public function processInit($workerID)
    {
        // 实例化模型

        // 查询是否存在指定的 worker_id
        $existingRecord = $this->processModel->where('worker_id', $workerID)->find();

        if (!$existingRecord) {
            // 如果记录不存在，则插入新记录
            $this->processModel->worker_id = $workerID;
            $this->processModel->status = 0;
            $this->processModel->spider_count = 0;
            $this->processModel->spider_ids = '';
            $this->processModel->save();
        } else {
            // 如果记录存在，则更新记录
            $existingRecord->status = 0;
            $existingRecord->spider_count = 0;
            $existingRecord->spider_ids = '';
            $existingRecord->save();
        }
    }

    public  function spiderInit($spiderID)
    {
        $this->spiderModel->where('id', $spiderID)->update(['status' => 0, 'process_ids' => '']);
    }

    public function setProcess($spiderId,$workerId)
    {
        $spider=$this->spiderModel->find($spiderId);
        if ($spider){
            if (empty($spider->process_ids)){
                $spider->process_ids=$workerId;
            }
            $spider->process_ids=$spider->process_ids.','.$workerId;
            $spider->save();
        }

        $process=$this->processModel->where('worker_id',$workerId)->find();
        if ($process){
            if (empty($process->spider_ids)){
                $process->spider_ids=$spiderId;
            }
            $process->spider_ids=$process->spider_ids.','.$spiderId;
            $process->spider_count=$process->spider_count+1;
            $process->save();
        }
    }


}
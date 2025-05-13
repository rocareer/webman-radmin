<?php


/**
 * File      WorkerService.php
 * Author    albert@rocareer.com
 * Time      2025-04-24 15:33:36
 * Describe  WorkerService.php
 */

namespace support\rocareer;

use app\admin\model\process\Worker;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;

class WorkerService
{

    protected $model;
    public function __construct(array $config = [])
    {
        $this->model=new Worker();
    }

    public function onStart($id,$pid)
    {
       try {
            $id = $id + 1;
            $worker = $this->model->find($id);
            if ($worker) {
                $worker->get_name = $this->getWorkerName($id);
                $worker->handler = 0;
                $worker->memory = 0;
                $worker->fails = 0;
                $worker->connections = 0;
                $worker->qps = 0;
                $worker->timers = 0;
                $worker->requests = 0;
                $worker->pid = $pid;
                $worker->save();

            } else {
                $this->model->id = $id;
                $this->model->name = $this->getWorkerName($id);
                $this->model->pid = $pid;
                $this->model->save();
            }
            return (int)$id;
        } catch (DataNotFoundException $e) {
            error_log($e->getMessage());
        }  catch (DbException $e) {
            error_log($e->getMessage());
        }
    }
    public function getWorkerName($id)
    {
        if ($id<10){
            $id="0$id";
        }
        return "Worker - $id";
    }
}
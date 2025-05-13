<?php


namespace plugin\radmin\middleware;

use plugin\radmin\app\admin\model\DataRecycle;
use plugin\radmin\app\admin\model\DataRecycleLog;
use plugin\radmin\app\admin\model\SensitiveData;
use plugin\radmin\app\admin\model\SensitiveDataLog;
use exception;
use plugin\radmin\extend\ba\TableManager;
use support\Log;
use upport\think\Db;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class AdminSecurity implements MiddlewareInterface
{

    protected array $listenAction = [
        'edit',
        'del',
        'delete',
    ];

    /**
     * @throws exception
     */
    public function process(Request $request, callable $handler): Response
    {

        $action = $request->action;
        if (!in_array($action, $this->listenAction)) {
            return $handler($request);
        }

        if ($action == 'del' || $action == 'delete') {

            $this->handleDeleteAction($request);
        } elseif ($action == 'edit') {
            $this->handleEditAction($request);
        } else {
            return $handler($request);
        }

        return $handler($request);
    }

    /**
     * @throws exception
     */
    protected function handleDeleteAction($request): void
    {
        try {
            $dataIds = $request->input('ids') ?? $request->input('id');
            if (empty($dataIds)) {
                return;
            }


            $recycle = DataRecycle::where('status', '1')
                ->where('controller_as', $request->controllerName)
                ->find();
            if (!$recycle) {
                return;
            }


            $recycleData = Db::connect(TableManager::getConnection($recycle['connection']))
                ->name($recycle['data_table'])
                ->whereIn($recycle['primary_key'], $dataIds)
                ->select()
                ->toArray();

            $recycleDataArr = [];


            foreach ($recycleData as $recycleDatum) {
                $recycleDataArr[] = [
                    'admin_id'    => $request->member->id,
                    'recycle_id'  => $recycle['id'],
                    'data'        => json_encode($recycleDatum, JSON_UNESCAPED_UNICODE),
                    'connection'  => $recycle['connection'],
                    'data_table'  => $recycle['data_table'],
                    'primary_key' => $recycle['primary_key'],
                    'ip'          => $request->getRealIp(),
                    'useragent'   => substr($request->header('user-agent'), 0, 255),
                ];
            }

            if (!$recycleDataArr) {
                return;
            }

            $dataRecycleLogModel = new DataRecycleLog();
            if (!$dataRecycleLogModel->saveAll($recycleDataArr)) {
                Log::channel('Radmin')
                    ->error('[DataSecurity] Failed to recycle data', $recycleDataArr);
            }
        } catch (Throwable $e) {
            Log::channel('Radmin')
                ->error('[DataSecurity-Delete] ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    protected function handleEditAction($request): void
    {

        try {
            $sensitiveData = SensitiveData::where('status', '1')
                ->where('controller_as', $request->controllerName)
                ->find();

            if (!$sensitiveData) {
                return;
            }

            $sensitiveData = $sensitiveData->toArray();
            $dataId        = $request->input($sensitiveData['primary_key']);
            $editData      = Db::connect(TableManager::getConnection($sensitiveData['connection']))
                ->name($sensitiveData['data_table'])
                ->field(array_keys($sensitiveData['data_fields']))
                ->where($sensitiveData['primary_key'], $dataId)
                ->find();

            if (!$editData) {
                return;
            }


            $newData          = $request->post();
            $sensitiveDataLog = [];

            foreach ($sensitiveData['data_fields'] as $field => $title) {
                if (isset($editData[$field]) && isset($newData[$field]) && $editData[$field] != $newData[$field]) {
                    if (stripos($field, 'password') !== false) {
                        if (!$newData[$field]) {
                            continue;
                        }
                        $newData[$field] = "******";
                    }

                    $sensitiveDataLog[] = [
                        'admin_id'     => $request->member->id,
                        'sensitive_id' => $sensitiveData['id'],
                        'connection'   => $sensitiveData['connection'],
                        'data_table'   => $sensitiveData['data_table'],
                        'primary_key'  => $sensitiveData['primary_key'],
                        'data_field'   => $field,
                        'data_comment' => $title,
                        'id_value'     => $dataId,
                        'before'       => $editData[$field],
                        'after'        => $newData[$field],
                        'ip'           => $request->getRealIp(),
                        'useragent'    => substr($request->header('user-agent'), 0, 255),
                    ];
                }
            }

            if (empty($sensitiveDataLog)) {
                return;
            }

            $sensitiveDataLogModel = new SensitiveDataLog();
            if (!$sensitiveDataLogModel->saveAll($sensitiveDataLog)) {
                Log::channel('Radmin')
                    ->warning('[DataSecurity] Sensitive data recording failed', $sensitiveDataLog);
            }
        } catch (Throwable $e) {
            Log::channel('Radmin')
                ->warning('[DataSecurity-Edit] ' . json_encode($e->getMessage()));
        }
    }
}
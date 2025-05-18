<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpPossiblePolymorphicInvocationInspection */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace plugin\radmin\app\admin\controller;

use exception;
use plugin\radmin\app\admin\library\module\Manage;
use plugin\radmin\app\admin\library\module\Server;
use plugin\radmin\app\admin\model\AdminLog;
use plugin\radmin\app\common\controller\Backend;
use Throwable;

class Module extends Backend
{
    protected array $noNeedPermission = ['state', 'dependentInstallComplete'];

    public function initialize():void
    {
        parent::initialize();
    }

    public function index(): \Radmin\Response
    {
     return $this->success('', [
            'sysVersion' =>  config('plugin.radmin.buildadmin.version'),
            'installed'  => Server::installedList(root_path() . 'modules' . DIRECTORY_SEPARATOR),
        ]);
    }

    public function state(): \Radmin\Response
    {
        $uid = $this->request->get("uid/s", '');
        if (!$uid) {
         return $this->error(__('Parameter error'));
        }
     return $this->success('', [
            'state' => Manage::instance($uid)->getInstallState()
        ]);
    }

    public function install(): \Radmin\Response
    {
        AdminLog::instance()->setTitle(__('Install module'));
        $uid     = $this->request->get("uid/s", '');
        $token   = $this->request->get("token/s", '');
        $orderId = $this->request->get("orderId/d", 0);
        if (!$uid) {
         return $this->error(__('Parameter error'));
        }
        $res = [];
        try {
            $res = Manage::instance($uid)->install($token, $orderId);
        } catch (Exception $e) {
         return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
        } catch (Throwable $e) {
         return $this->error(__($e->getMessage()));
        }
     return $this->success('', [
            'data' => $res,
        ]);
    }

    public function dependentInstallComplete(): \Radmin\Response
    {
        $uid = $this->request->get("uid/s", '');
        if (!$uid) {
         return $this->error(__('Parameter error'));
        }
        try {
            Manage::instance($uid)->dependentInstallComplete('all');
        } catch (Exception $e) {
         return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
        } catch (Throwable $e) {
         return $this->error(__($e->getMessage()));
        }
     return $this->success();
    }

    public function changeState(): \Radmin\Response
    {
        AdminLog::instance()->setTitle(__('Change module state'));
        $uid   = $this->request->post("uid/s", '');
        $state = $this->request->post("state/b", false);
        if (!$uid) {
         return $this->error(__('Parameter error'));
        }
        $info = [];
        try {
            $info = Manage::instance($uid)->changeState($state);
        } catch (Exception $e) {
         return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
        } catch (Throwable $e) {
         return $this->error(__($e->getMessage()));
        }
     return $this->success('', [
            'info' => $info,
        ]);
    }

    public function uninstall(): \Radmin\Response
    {
        AdminLog::instance()->setTitle(__('Unload module'));
        $uid = $this->request->get("uid/s", '');
        if (!$uid) {
         return $this->error(__('Parameter error'));
        }
        try {
            Manage::instance($uid)->uninstall();
        } catch (Exception $e) {
         return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
        } catch (Throwable $e) {
         return $this->error(__($e->getMessage()));
        }
     return $this->success();
    }

    public function update(): \Radmin\Response
    {
        AdminLog::instance()->setTitle(__('Update module'));
        $uid     = $this->request->get("uid/s", '');
        $token   = $this->request->get("token/s", '');
        $orderId = $this->request->get("orderId/d", 0);
        if (!$token || !$uid) {
         return $this->error(__('Parameter error'));
        }
        try {
            Manage::instance($uid)->update($token, $orderId);
        } catch (Exception $e) {
         return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
        } catch (Throwable $e) {
         return $this->error(__($e->getMessage()));
        }
     return $this->success();
    }

    public function upload(): \Radmin\Response
    {
        AdminLog::instance()->setTitle(__('Upload install module'));
        $file  = $this->request->get("file/s", '');
        $token = $this->request->get("token/s", '');
        if (!$file) $this->error(__('Parameter error'));
        if (!$token) $this->error(__('Please login to the official website account first'));

        $info = [];
        try {
            $info = Manage::instance()->upload($token, $file);
        } catch (Exception $e) {
         return $this->error(__($e->getMessage()), $e->getData(), $e->getCode());
        } catch (Throwable $e) {
         return $this->error(__($e->getMessage()));
        }
     return $this->success('', [
            'info' => $info
        ]);
    }
}
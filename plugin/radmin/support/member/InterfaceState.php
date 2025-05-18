<?php


namespace plugin\radmin\support\member;

/**
 * 状态管理器接口
 */
interface InterfaceState
{
    /**
     * 获取状态值
     */

    public function checkStatus($member): bool;

}
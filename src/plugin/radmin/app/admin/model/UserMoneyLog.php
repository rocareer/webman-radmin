<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use Throwable;
use think\Exception;
use think\model\relation\BelongsTo;

/**
 * UserMoneyLog 模型
 * 1. 创建余额日志自动完成会员余额的添加
 * 2. 创建余额日志时，请开启事务
 */
class UserMoneyLog extends BaseModel
{
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    protected $type = [
        'money'       => 'integer',
        'after'       => 'integer',
        'before'      => 'integer',
        'create_time' => 'integer',
        'update_time' => 'integer'
    ];

    /**
     * 入库前
     * @throws Throwable
     */
    public static function onBeforeInsert($model): void
    {
        $user = User::where('id', $model->user_id)->lock(true)->find();
        if (!$user) {
            throw new Exception("The user can't find it");
        }
        if (!$model->memo) {
            throw new Exception("Change note cannot be blank");
        }
        $model->before = $user->money;


        //todo for ba
        $user->money +=$model->money*100;


        $user->save();
        $model->after =bcmul($user->money,1,2);

    }

    public static function onBeforeDelete(): bool
    {
        return false;
    }

    public function getMoneyAttr($value): string
    {
        if ($value<100){
           return bcdiv($value, 100, 4);
        }
        return bcdiv($value, 100, 2);
    }

    public function setMoneyAttr($value): string
    {
        return bcmul($value, 100, 2);
    }

    public function getBeforeAttr($value): string
    {
        return bcdiv($value, 100, 2);
    }

    public function setBeforeAttr($value): string
    {
        return bcmul($value, 100, 2);
    }

    public function getAfterAttr($value): string
    {
        return bcdiv($value, 100, 2);
    }

    public function setAfterAttr($value): string
    {
        return bcmul($value, 100, 2);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
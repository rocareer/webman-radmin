<?php
/** @noinspection PhpUnusedParameterInspection */

namespace plugin\radmin\app\admin\model;

use plugin\radmin\app\common\model\BaseModel;
use plugin\radmin\exception\BusinessException;
use Radmin\cache\Cache;
use Throwable;


/**
 * 系统配置模型
 * @property mixed $content
 * @property mixed $rule
 * @property mixed $extend
 * @property mixed $allow_del
 */
class Config extends BaseModel
{
    public static string $cacheTag = 'sys_config';

    protected $autoWriteTimestamp=false;

    protected array $append = [
        'value',
        'content',
        'extend',
        'input_extend',
    ];


    protected array $jsonDecodeType = ['checkbox', 'array', 'selects'];
    protected array $needContent    = ['radio', 'checkbox', 'select', 'selects'];

    /**
     * 入库前
     * @throws Throwable
     */
    public static function onBeforeInsert(Config $model): void
    {
       try {
            if (!in_array($model->getData('type'), $model->needContent)) {
                $model->content = null;
            } else {
                $model->content = json_encode(str_attr_to_array($model->getData('content')));
            }

            if (is_array($model->rule)) {
                $model->rule = implode(',', $model->rule);
            }
            if ($model->getData('extend') || $model->getData('inputExtend')) {
                $extend      = str_attr_to_array($model->getData('extend'));
                $inputExtend = str_attr_to_array($model->getData('inputExtend'));
                if ($inputExtend) $extend['baInputExtend'] = $inputExtend;
                if ($extend) $model->extend = json_encode($extend);
            }
            $model->allow_del = 1;
        } catch (\Exception $e) {
            throw new BusinessException($e->getMessage());
        }
    }

    /**
     * 写入后
     */
    public static function onAfterWrite(): void
    {
        // 清理配置缓存
        Cache::tag(self::$cacheTag)->clear();
    }

    public function getValueAttr($value, $row)
    {
        if (!isset($row['type']) || $value == '0') return $value;
        if (in_array($row['type'], $this->jsonDecodeType)) {
            return empty($value) ? [] : json_decode($value, true);
        } elseif ($row['type'] == 'switch') {
            return (bool)$value;
        } elseif ($row['type'] == 'editor') {
            return !$value ? '' : htmlspecialchars_decode($value);
        } elseif (in_array($row['type'], ['city', 'remoteSelects'])) {
            if (!$value) return [];
            if (!is_array($value)) return explode(',', $value);
            return $value;
        } else {
            return $value ?: '';
        }
    }

    public function setValueAttr(mixed $value, $row): mixed
    {try {
            if (in_array($row['type'], $this->jsonDecodeType)) {
                return $value ? json_encode($value) : '';
            } elseif ($row['type'] == 'switch') {
                return $value ? '1' : '0';
            } elseif ($row['type'] == 'time') {
                return $value ? date('H:i:s', strtotime($value)) : '';
            } elseif ($row['type'] == 'city') {
                if ($value && is_array($value)) {
                    return implode(',', $value);
                }
                return $value ?: '';
            } elseif (is_array($value)) {
                return implode(',', $value);
            }
            return $value;
        } catch (\Exception $e) {
            throw new BusinessException($e->getMessage());
        }
    }

    public function getContentAttr($value, $row)
    {
        if (!isset($row['type'])) return '';
        if (in_array($row['type'], $this->needContent)) {
            $arr = json_decode($value, true);
            return $arr ?: [];
        } else {
            return '';
        }
    }

    public function getExtendAttr($value)
    {
        if ($value) {
            $arr = json_decode($value, true);
            if ($arr) {
                unset($arr['baInputExtend']);
                return $arr;
            }
        }
        return [];
    }

    public function getInputExtendAttr($value, $row)
    {
        try {
            if ($row && $row['extend']) {
                $arr = json_decode($row['extend'], true);
                if ($arr && isset($arr['baInputExtend'])) {
                    return $arr['baInputExtend'];
                }
            }
            return [];
        } catch (\Exception $e) {
            throw new BusinessException($e->getMessage());
        }
    }
}
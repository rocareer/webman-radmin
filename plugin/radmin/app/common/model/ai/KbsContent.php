<?php


namespace plugin\radmin\app\common\model\ai;

use plugin\radmin\app\common\model\BaseModel;
use plugin\radmin\app\common\library\ai\Helper;

/**
 * KbsContent
 */
class KbsContent extends BaseModel
{
    // 表名
    protected $name = 'ai_kbs_content';

	protected $pk='id';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    protected $type = [
        'extend' => 'array',
        'update_time'=>'integer',
        'create_time'=>'integer'
    ];

    // 追加属性
    protected $append = [
        'aiKbs',
    ];

    protected static function onAfterInsert($model)
    {
        if ($model->weigh == 0) {
            $pk = $model->getPk();
            if (strlen($model[$pk]) >= 19) {
                $model->where($pk, $model[$pk])->update(['weigh' => $model->count()]);
            } else {
                $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
            }
        }
    }

    public function getEmbeddingAttr($value): bool|array
    {
        $config = Helper::getConfig();
        $format = Helper::$embeddingModelAttr[$config['ai_api_type']]['vector_type'] == 'FLOAT32' ? 'f' : 'd';
        return $value ? array_merge(unpack("$format*", $value)) : [];
    }

    public function setEmbeddingAttr($value): ?string
    {
        $config = Helper::getConfig();
        $format = Helper::$embeddingModelAttr[$config['ai_api_type']]['vector_type'] == 'FLOAT32' ? 'f' : 'd';
        return $value ? pack("$format*", ...$value) : null;
    }

    public function getAiKbsAttr($value, $row): array
    {
        return [
	        'name' => \app\common\model\ai\Kbs::whereIn('id', $row['ai_kbs_ids'])->column('name'),
        ];
    }

    public function getAiKbsIdsAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setAiKbsIdsAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function getContentAttr($value, $row): string
    {
        if ($row['content_source'] == 'quote' && $row['content_quote']) {
            $quote = KbsContent::where('id', $row['content_quote'])->withAttr('content', function ($value) use ($row) {
                $value = str_replace('${title}', $row['title'], $value);
                return !$value ? '' : htmlspecialchars_decode($value);
            })->find();
            return $quote->content;
        }
        $value = str_replace('${title}', $row['title'], $value);
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}
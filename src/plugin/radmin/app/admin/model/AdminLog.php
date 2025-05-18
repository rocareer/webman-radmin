<?php

namespace plugin\radmin\app\admin\model;

use Exception;
use plugin\radmin\app\common\model\BaseModel;
use plugin\radmin\support\Http;
use plugin\radmin\support\orm\Rdb;
use think\model\relation\BelongsTo;


/**
 * AdminLog模型
 */
class AdminLog extends BaseModel
{
    protected $autoWriteTimestamp = true;
    /** @noinspection PhpMissingFieldTypeInspection */
    protected $updateTime = false;

    /**
     * 自定义日志标题
     * @var string
     */
    protected string $title = '';
    /** @noinspection PhpMissingFieldTypeInspection */
    protected $validate = [];

    /**
     * 自定义日志内容
     * @var string|array
     */
    protected string|array $data = '';

    /**
     * 忽略的链接正则列表
     * @var array
     */
    protected array $urlIgnoreRegex = [
        '/^(.*)\/(select|index|logout)$/i',
    ];

    protected array $desensitizationRegex = [
        '/(password|salt|token)/i'
    ];

    protected $name='admin_log';

    protected $pk='id';
    public function getTable(bool $alias = false): string
    {
        return getDbPrefix().$this->name;
    }

    public static function instance()
    {
        $request = Http::request();
        if (!isset($request->adminLog)) {
            $request->adminLog = new static();
        }

        return $request->adminLog;
    }

    /**
     * 设置标题
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 注意 name 只接受 string
     * 设置日志内容
     * @param string $name
     * @param null   $value
     */
    public function setData(string $name, $value = null): void
    {

        $this->data = $name;
    }

    /**
     * 设置忽略的链接正则列表
     * @param array|string $regex
     */
    public function setUrlIgnoreRegex(array|string $regex = []): void
    {
        $regex                = is_array($regex) ? $regex : [$regex];
        $this->urlIgnoreRegex = array_merge($this->urlIgnoreRegex, $regex);
    }

    /**
     * 设置需要进行数据脱敏的正则列表
     * @param array|string $regex
     */
    public function setDesensitizationRegex(array|string $regex = []): void
    {
        $regex                      = is_array($regex) ? $regex : [$regex];
        $this->desensitizationRegex = array_merge($this->desensitizationRegex, $regex);
    }

    /**
     * 数据脱敏（只数组，根据数组 key 脱敏）
     * @param array|string|null $data
     * @return array|string|null
     */
    protected function desensitization(array|string|null $data): array|string|null
    {
        if ($data===null){
            return null;
        }
        if (!is_array($data) || !$this->desensitizationRegex) {
            return $data;
        }
        foreach ($data as $index => &$item) {
            foreach ($this->desensitizationRegex as $reg) {
                if (preg_match($reg, $index)) {
                    $item = "***";
                } elseif (is_array($item)) {
                    $item = $this->desensitization($item);
                }
            }
        }
        return $data;
    }

    /**
     * 写入日志
     * @param string     $title
     * @param mixed|null $data
     * @throws Exception
     */
    public function record(string $title = '', mixed $data = null):void
    {
        $adminId=null;
        $userName=null;
        if (Http::request()->member){
            $userID = Http::request()->member->id;
            $userName = Http::request()->member->username;
        }
        $adminId    = $adminId ?? $userID??0 ;
        $username   = $userName ?? Http::request()->input('username', __('Unknown'));
        $controller = str_replace('.', '/', Http::request()->controller());
        $action     = Http::request()->action;
        $path       = $controller . '/' . $action;
        if ($this->urlIgnoreRegex) {
            foreach ($this->urlIgnoreRegex as $item) {
                if (preg_match($item, $path)) {
                    return;
                }
            }
        }
        $data = $data ?: $this->data;
        if (empty($data)) {
            $data = Http::request()->all();
        }
        $data  = $this->desensitization($data);
        $title = $title ?: $this->title;
        if (!$title) {
            $controllerTitle = AdminRule::where('name', $controller)->value('title');
            $title           = AdminRule::where('name', $path)->value('title');
            $title           = $title ?: __('Unknown') . '(' . $action . ')';
            $title           = $controllerTitle ? ($controllerTitle . '-' . $title) : $title;
        }
        Rdb::name('admin_log')->insert([
            'admin_id'  => $adminId,
            'username'  => $username,
            'url'       => substr(
                str_replace(
                    '//app/radmin/', '',
                    str_replace
                    (
                        Http::request()->host(), '', Http::request()->url()
                    )
                ), 0, 1500
            ),
            'title'     => $title,
            'data'      => !is_scalar($data) ? json_encode($data) : $data,
            'ip'        => Http::request()->getRealIp(),
            'useragent' => substr(Http::request()->header('user-agent'), 0, 255),
            'create_time'=> time(),
        ]);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
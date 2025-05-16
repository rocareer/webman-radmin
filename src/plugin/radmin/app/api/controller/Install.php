<?php
/**
 * File:        Install.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/14 10:17
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */


namespace plugin\radmin\app\api\controller;


use plugin\radmin\app\process\Http;
use Exception;
use plugin\radmin\app\admin\model\Config;
use plugin\radmin\exception\BusinessException;
use plugin\radmin\support\member\admin\AdminModel;
use plugin\radmin\support\Response;
use think\db\exception\DbException;
use Throwable;
use PDOException;
use plugin\radmin\support\think\orm\Rdb;
use plugin\radmin\extend\ba\Random;
use plugin\radmin\extend\ba\Version;
use plugin\radmin\extend\ba\Terminal;
use plugin\radmin\extend\ba\Filesystem;
use plugin\radmin\app\admin\model\User as UserModel;

/**
 * 安装控制器
 */
class Install
{
    //todo
    protected bool $useSystemSettings = false;

    /**
     * 环境检查状态
     */
    static string $ok   = 'ok';
    static string $fail = 'fail';
    static string $warn = 'warn';

    /**
     * 安装锁文件名称
     */
    static string $lockFileName = 'install.lock';

    /**
     * 配置文件
     */
    //    static string $dbConfigFileName    = 'database.php';
    static string $dbConfigFileName    = 'think-orm.php';
    static string $buildConfigFileName = 'buildadmin.php';

    /**
     * 自动构建的前端文件的 outDir 相对于根目录
     */
    static string $distDir = 'web' . DIRECTORY_SEPARATOR . 'dist';

    /**
     * 需要的依赖版本
     */
    static array $needDependentVersion = [
        'php'  => '8.0.2',
        'npm'  => '9.8.1',
        'cnpm' => '7.1.0',
        'node' => '20.14.0',
        'yarn' => '1.2.0',
        'pnpm' => '6.32.13',
    ];

    /**
     * 安装完成标记
     * 配置完成则建立lock文件
     * 执行命令成功执行再写入标记到lock文件
     * 实现命令执行失败，重载页面可重新执行
     */
    static string $InstallationCompletionMark = 'install-end';


    protected $request;

    public function __construct()
    {
        $this->request = Http::request();
    }

    /**
     * 命令执行窗口
     * @throws Throwable
     */
    public function terminal()
    {
        if ($this->isInstallComplete()) {
            return;
        }

        (new Terminal())->exec(false);
    }

    public function changePackageManager()
    {
        if ($this->isInstallComplete()) {
            return;
        }

        $newPackageManager = Http::request()->post('manager', config('plugin.radmin.terminal.npm_package_manager'));
        if (Terminal::changeTerminalConfig()) {
            return $this->success('', [
                'manager' => $newPackageManager
            ]);
        } else {
            return $this->error(__('Failed to switch package manager. Please modify the configuration file manually:%s', ['plugin/radmin/config/buildadmin.php']));
        }
    }

    /**
     * 环境基础检查
     */
    public function envBaseCheck()
    {
        if ($this->isInstallComplete()) {
            return $this->error(__('The system has completed installation. If you need to reinstall, please delete the %s file first', ['plugin/radmin/public/' . self::$lockFileName]), []);
        }
        //        if (getenv('THINKORM_DEFAULT_TYPE')) {
        //            return $this->error(__('检测到带有数据库配置的 .env 文件。请清理后再试一次!'));
        //        }

        // php版本-start
        $phpVersion        = phpversion();
        $phpVersionCompare = Version::compare(self::$needDependentVersion['php'], $phpVersion);
        if (!$phpVersionCompare) {
            $phpVersionLink = [
                [
                    // 需要PHP版本
                    'name' => __('need') . ' >= ' . self::$needDependentVersion['php'],
                    'type' => 'text'
                ],
                [
                    // 如何解决
                    'name'  => __('How to solve?'),
                    'title' => __('Click to see how to solve it'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/preparePHP.html'
                ]
            ];
        }
        // php版本-end

        // 配置文件-start
        $dbConfigFile     = base_path() . '/plugin/radmin/config/' . self::$dbConfigFileName;
        $configIsWritable = Filesystem::pathIsWritable(base_path() . '/plugin/radmin/config') && Filesystem::pathIsWritable($dbConfigFile);
        if (!$configIsWritable) {
            $configIsWritableLink = [
                [
                    // 查看原因
                    'name'  => __('View reason'),
                    'title' => __('Click to view the reason'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/dirNoPermission.html'
                ]
            ];
        }
        // 配置文件-end

        // public-start
        $publicIsWritable = Filesystem::pathIsWritable(base_path() . '/plugin/radmin/public');
        if (!$publicIsWritable) {
            $publicIsWritableLink = [
                [
                    'name'  => __('View reason'),
                    'title' => __('Click to view the reason'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/dirNoPermission.html'
                ]
            ];
        }
        // public-end

        // PDO-start
        $phpPdo = extension_loaded("PDO") && extension_loaded('pdo_mysql');
        if (!$phpPdo) {
            $phpPdoLink = [
                [
                    'name' => __('PDO extensions need to be installed'),
                    'type' => 'text'
                ],
                [
                    'name'  => __('How to solve?'),
                    'title' => __('Click to see how to solve it'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/missingExtension.html'
                ]
            ];
        }
        // PDO-end

        // GD2和freeType-start
        $phpGd2 = extension_loaded('gd') && function_exists('imagettftext');
        if (!$phpGd2) {
            $phpGd2Link = [
                [
                    'name' => __('The gd extension and freeType library need to be installed'),
                    'type' => 'text'
                ],
                [
                    'name'  => __('How to solve?'),
                    'title' => __('Click to see how to solve it'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/gdFail.html'
                ]
            ];
        }
        // GD2和freeType-end

        // proc_open
        $phpProc = function_exists('proc_open') && function_exists('proc_close') && function_exists('proc_get_status');
        if (!$phpProc) {
            $phpProcLink = [
                [
                    'name'  => __('View reason'),
                    'title' => __('proc_open or proc_close functions in PHP Ini is disabled'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/disablement.html'
                ],
                [
                    'name'  => __('How to modify'),
                    'title' => __('Click to view how to modify'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/disablement.html'
                ],
                [
                    'name'  => __('Security assurance?'),
                    'title' => __('Using the installation service correctly will not cause any potential security problems. Click to view the details'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/senior.html'
                ],
            ];
        }
        // proc_open-end

        return $this->success('', [
            'php_version'        => [
                'describe' => $phpVersion,
                'state'    => $phpVersionCompare ? self::$ok : self::$fail,
                'link'     => $phpVersionLink ?? [],
            ],
            'config_is_writable' => [
                'describe' => self::writableStateDescribe($configIsWritable),
                'state'    => $configIsWritable ? self::$ok : self::$fail,
                'link'     => $configIsWritableLink ?? []
            ],
            'public_is_writable' => [
                'describe' => self::writableStateDescribe($publicIsWritable),
                'state'    => $publicIsWritable ? self::$ok : self::$fail,
                'link'     => $publicIsWritableLink ?? []
            ],
            'php_pdo'            => [
                'describe' => $phpPdo ? __('already installed') : __('Not installed'),
                'state'    => $phpPdo ? self::$ok : self::$fail,
                'link'     => $phpPdoLink ?? []
            ],
            'php_gd2'            => [
                'describe' => $phpGd2 ? __('already installed') : __('Not installed'),
                'state'    => $phpGd2 ? self::$ok : self::$fail,
                'link'     => $phpGd2Link ?? []
            ],
            'php_proc'           => [
                'describe' => $phpProc ? __('Allow execution') : __('disabled'),
                'state'    => $phpProc ? self::$ok : self::$warn,
                'link'     => $phpProcLink ?? []
            ],
        ]);
    }

    /**
     * npm环境检查
     */
    public function envNpmCheck()
    {
        if ($this->isInstallComplete()) {
            return $this->error('', [], 2);
        }

        $packageManager = Http::request()->post('manager', 'none');

        // npm
        $npmVersion        = Version::getVersion('npm');
        $npmVersionCompare = Version::compare(self::$needDependentVersion['npm'], $npmVersion);
        if (!$npmVersionCompare || !$npmVersion) {
            $npmVersionLink = [
                [
                    // 需要版本
                    'name' => __('need') . ' >= ' . self::$needDependentVersion['npm'],
                    'type' => 'text'
                ],
                [
                    // 如何解决
                    'name'  => __('How to solve?'),
                    'title' => __('Click to see how to solve it'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/prepareNpm.html'
                ]
            ];
        }

        // 包管理器
        if (in_array($packageManager, ['npm', 'cnpm', 'pnpm', 'yarn'])) {
            $pmVersion        = Version::getVersion($packageManager);
            $pmVersionCompare = Version::compare(self::$needDependentVersion[$packageManager], $pmVersion);

            if (!$pmVersion) {
                // 安装
                $pmVersionLink[] = [
                    // 需要版本
                    'name' => __('need') . ' >= ' . self::$needDependentVersion[$packageManager],
                    'type' => 'text'
                ];
                if ($npmVersionCompare) {
                    $pmVersionLink[] = [
                        // 点击安装
                        'name'  => __('Click Install %s', [$packageManager]),
                        'title' => '',
                        'type'  => 'install-package-manager'
                    ];
                } else {
                    $pmVersionLink[] = [
                        // 请先安装npm
                        'name' => __('Please install NPM first'),
                        'type' => 'text'
                    ];
                }
            } elseif (!$pmVersionCompare) {
                // 版本不足
                $pmVersionLink[] = [
                    // 需要版本
                    'name' => __('need') . ' >= ' . self::$needDependentVersion[$packageManager],
                    'type' => 'text'
                ];
                $pmVersionLink[] = [
                    // 请升级
                    'name' => __('Please upgrade %s version', [$packageManager]),
                    'type' => 'text'
                ];
            }
        } elseif ($packageManager == 'ni') {
            $pmVersion        = __('nothing');
            $pmVersionCompare = true;
        } else {
            $pmVersion        = __('nothing');
            $pmVersionCompare = false;
        }

        // nodejs
        $nodejsVersion        = Version::getVersion('node');
        $nodejsVersionCompare = Version::compare(self::$needDependentVersion['node'], $nodejsVersion);
        if (!$nodejsVersionCompare || !$nodejsVersion) {
            $nodejsVersionLink = [
                [
                    // 需要版本
                    'name' => __('need') . ' >= ' . self::$needDependentVersion['node'],
                    'type' => 'text'
                ],
                [
                    // 如何解决
                    'name'  => __('How to solve?'),
                    'title' => __('Click to see how to solve it'),
                    'type'  => 'faq',
                    'url'   => 'https://doc.buildadmin.com/guide/install/prepareNodeJs.html'
                ]
            ];
        }

        return $this->success('', [
            'npm_version'         => [
                'describe' => $npmVersion ?: __('Acquisition failed'),
                'state'    => $npmVersionCompare ? self::$ok : self::$warn,
                'link'     => $npmVersionLink ?? [],
            ],
            'nodejs_version'      => [
                'describe' => $nodejsVersion ?: __('Acquisition failed'),
                'state'    => $nodejsVersionCompare ? self::$ok : self::$warn,
                'link'     => $nodejsVersionLink ?? []
            ],
            'npm_package_manager' => [
                'describe' => $pmVersion ?: __('Acquisition failed'),
                'state'    => $pmVersionCompare ? self::$ok : self::$warn,
                'link'     => $pmVersionLink ?? [],
            ]
        ]);
    }

    /**
     * 测试数据库连接
     */
    public function testDatabase()
    {
        $database = [
            'hostname' => $this->request->input('hostname'),
            'username' => $this->request->input('username'),
            'password' => $this->request->input('password'),
            'hostport' => $this->request->input('hostport'),
            'database' => '',
        ];


        $conn = $this->connectDb($database);
        if ($conn['code'] == 0) {
            return $this->error($conn['msg']);
        } else {
            return $this->success('', [
                'databases' => $conn['databases']
            ]);
        }
    }

    /**
     * 系统基础配置
     * post请求=开始安装
     */
    public function baseConfig()
    {
        if ($this->isInstallComplete()) {
            return $this->error(__('The system has completed installation. If you need to reinstall, please delete the %s file first', ['public/' . self::$lockFileName]));
        }


        $envOk    = $this->commandExecutionCheck();
        $rootPath = str_replace('\\', '/', base_path());
        if (Http::request()->isGet()) {
            return $this->success('', [
                'rootPath'            => $rootPath,
                'executionWebCommand' => $envOk
            ]);
        }

        $connectData = $databaseParam = Http::request()->only(['hostname', 'username', 'password', 'hostport', 'database', 'prefix']);

        // 数据库配置测试
        $connectData['database'] = '';

        $connect = $this->connectDb($connectData, true);
        if ($connect['code'] == 0) {
            return $this->error($connect['msg']);
        }


        // 建立数据库
        if (!in_array($databaseParam['database'], $connect['databases'])) {
            $sql = "CREATE DATABASE IF NOT EXISTS `{$databaseParam['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            $connect['pdo']->exec($sql);
        }

        // 写入数据库配置文件
        $dbConfigFile = base_path() . '/plugin/radmin/config/' . self::$dbConfigFileName;


        try {
            $dbConfigContent = @file_get_contents($dbConfigFile);
            if (!$dbConfigContent) {
                return $this->error(__('File not found: %s', ['config/' . self::$dbConfigFileName]));
            }

            $callback = function ($matches) use ($databaseParam) {
                // 从 $databaseParam 中获取对应的值
                $key   = $matches[1];
                $value = $databaseParam[$key] ?? $matches[4]; // 如果 $databaseParam 中没有值，则保留原来的默认值

                // 特殊处理 hostport，因为环境变量名称是 THINKORM_DEFAULT_PORT
                $envKey = ($key == 'hostport') ? 'PORT' : strtoupper($key);

                return "'{$key}' => getenv('THINKORM_DEFAULT_{$envKey}', '{$value}'),";
            };

            $dbConfigText = preg_replace_callback(
                "/'(hostname|database|username|password|hostport|prefix)'(\s+)=>(\s+)getenv\('.*?',\s*'(.*?)'\),/",
                $callback,
                $dbConfigContent
            );

            $result = @file_put_contents($dbConfigFile, $dbConfigText);

            if (!$result) {
                return $this->error(__('File has no write permission: %s', ['config/' . self::$dbConfigFileName]));
            }

        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        // 写入环境变量配置文件
        try {
            $envFile = base_path() . '/.env-example';
            $env     = base_path() . '/.env';

            // 读取现有的环境变量文件内容
            $envFileContent = is_file($envFile) ? file_get_contents($envFile) : '';
            if ($envFileContent === false) {
                throw new \Exception(__('Failed to read file:%s', ['/.env-example']));
            }

            // 清理已有的数据库配置
            $databasePos = stripos($envFileContent, '#THINKORM');
            if ($databasePos !== false) {
                $envFileContent = substr($envFileContent, 0, $databasePos);
            }

            // 准备新的数据库配置
            $envConfig = [
                '#THINKORM',
                'THINKORM_DEFAULT_TYPE=mysql',
                'THINKORM_DEFAULT_HOSTNAME=' . $databaseParam['hostname'],
                'THINKORM_DEFAULT_DATABASE=' . $databaseParam['database'],
                'THINKORM_DEFAULT_USERNAME=' . $databaseParam['username'],
                'THINKORM_DEFAULT_PASSWORD=' . $databaseParam['password'],
                'THINKORM_DEFAULT_PORT=' . $databaseParam['hostport'],
                'THINKORM_DEFAULT_PREFIX=' . $databaseParam['prefix'],
                'THINKORM_DEFAULT_CHARSET=utf8mb4',
                'THINKORM_DEFAULT_DEBUG=true'
            ];

            // 合并现有内容和新配置
            $envFileContent = rtrim($envFileContent, "\n") . "\n\n" . implode("\n", $envConfig) . "\n";

            // 写入 .env-example 文件
            if (file_put_contents($envFile, $envFileContent) === false) {
                throw new \Exception(__('File has no write permission:%s', ['/.env-example']));
            }

            // 写入 .env 文件
            if (file_put_contents($env, $envFileContent) === false) {
                throw new \Exception(__('File has no write permission:%s', ['/.env']));
            }

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        // 设置新的Token随机密钥key
        $oldTokenKey        = config('plugin.radmin.buildadmin.token.key');
        $newTokenKey        = Random::build('alnum', 32);
        $buildConfigFile    = base_path() . '/plugin/radmin/config/' . self::$buildConfigFileName;
        $buildConfigContent = @file_get_contents($buildConfigFile);
        $buildConfigContent = preg_replace("/'key'(\s+)=>(\s+)'$oldTokenKey'/", "'key'\$1=>\$2'$newTokenKey'", $buildConfigContent);
        $result             = @file_put_contents($buildConfigFile, $buildConfigContent);
        if (!$result) {
            return $this->error(__('File has no write permission:%s', ['/plugin/radmin/config/' . self::$buildConfigFileName]));
        }

        // 建立安装锁文件
        // $result = @file_put_contents(base_path().'/plugin/radmin/public/' . self::$lockFileName, date('Y-m-d H:i:s'));
        // if (!$result) {
        //     return $this->error(__('File has no write permission:%s', ['plugin/radmin/public/' . self::$lockFileName]));
        // }


        return $this->success('', [
            'rootPath'            => $rootPath,
            'executionWebCommand' => $envOk
        ]);
    }

    protected function isInstallComplete(): bool
    {
        if (is_file(base_path() . '/plugin/radmin/public/' . self::$lockFileName)) {
            $contents = @file_get_contents(base_path() . '/plugin/radmin/public/' . self::$lockFileName);
            if ($contents == self::$InstallationCompletionMark) {
                return true;
            }
        }
        return false;
    }

    /**
     * 标记命令执行完毕
     * @throws Throwable
     */
    public function commandExecComplete(): Response
    {

        try {
            if ($this->isInstallComplete()) {
                return $this->error(__('The system has completed installation. If you need to reinstall, please delete the %s file first', ['public/' . self::$lockFileName]));
            }
            $param = $this->request->only(['type', 'adminname', 'adminpassword', 'sitename']);
            if ($param['type'] == 'web') {
                $result = @file_put_contents(base_path() . '/plugin/radmin/public/' . self::$lockFileName, self::$InstallationCompletionMark);
                if (!$result) {
                    return $this->error(__('File has no write permission:%s', ['plugin/radmin/public/' . self::$lockFileName]));
                }
            } else {
                // 管理员配置入库

                $saveData = [
                    'username' => $param['adminname'],
                    'nickname' => ucfirst($param['adminname']),
                ];
                if (isset($param['adminpassword']) && $param['adminpassword']) {
                    $salt                 = Random::build('alnum', 16);
                    $passwd               = hash_password($param['adminpassword'], $salt);
                    $saveData["password"] = $passwd;
                    $saveData['salt']     = $salt;
                }
                try {
                    // (new Terminal)->exec(false,'worker.reload');
                    // 管理员配置入库
                    $adminModel             = new AdminModel();
                    $defaultAdmin           = $adminModel->where('username', 'admin')->find();
                    $defaultAdmin->username = $param['adminname'];
                    $defaultAdmin->nickname = ucfirst($param['adminname']);
                    $defaultAdmin->save();

                    if (isset($param['adminpassword']) && $param['adminpassword']) {
                        $adminModel->resetPassword($defaultAdmin->id, $param['adminpassword']);
                    }

                    // 默认用户密码修改
                    $user = new UserModel();
                    $user->resetPassword(1, Random::build());
                    Config::where('name', 'site_name')->update([
                        'value' => $param['sitename']
                    ]);
                } catch (DbException $e) {
                    throw new BusinessException($e->getMessage(), $e->getCode(), false, [], $e);
                }
            }

            return $this->success();
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * 获取命令执行检查的结果
     * @return bool 是否拥有执行命令的条件
     */
    private function commandExecutionCheck(): bool
    {
        $pm = config('plugin.radmin.terminal.npm_package_manager');
        if ($pm == 'none') {
            return false;
        }
        $check['phpPopen']             = function_exists('proc_open') && function_exists('proc_close');
        $check['npmVersionCompare']    = Version::compare(self::$needDependentVersion['npm'], Version::getVersion('npm'));
        $check['pmVersionCompare']     = Version::compare(self::$needDependentVersion[$pm], Version::getVersion($pm));
        $check['nodejsVersionCompare'] = Version::compare(self::$needDependentVersion['node'], Version::getVersion('node'));

        $envOk = true;
        foreach ($check as $value) {
            if (!$value) {
                $envOk = false;
                break;
            }
        }
        return $envOk;
    }

    /**
     * 安装指引
     */
    public function manualInstall()
    {
        return $this->success('', [
            'webPath' => str_replace('\\', '/', base_path() . '/web')
        ]);
    }

    public function mvDist()
    {
        if (!is_file(base_path() . self::$distDir . DIRECTORY_SEPARATOR . 'index.html')) {
            return $this->error(__('No built front-end file found, please rebuild manually!'));
        }

        if (Terminal::mvDist()) {
            // copy(base_path().'/vendor/rocareer/radmin/plugin/radmin/config/event.php',base_path().'/plugin/radmin/config/event.php');
            return $this->success();
        } else {
            return $this->error(__('Failed to move the front-end file, please move it manually!'));
        }
    }

    /**
     * 目录是否可写
     * @param $writable
     * @return string
     */
    private static function writableStateDescribe($writable): string
    {
        return $writable ? __('Writable') : __('No write permission');
    }

    /**
     * 数据库连接-获取数据表列表
     * @param array $database
     * @param bool  $returnPdo
     * @return array
     */
    private function connectDb(array $database, bool $returnPdo = false): array
    {

        try {
            $dbConfig                         = config('plugin.radmin.think-orm');
            $dbConfig['connections']['mysql'] = array_merge($dbConfig['connections']['mysql'], $database);

            Rdb::setConfig($dbConfig);
            Rdb::connect('mysql');
            Rdb::execute("SELECT 1");

        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();
            return [
                'code' => 0,
                'msg'  => __('Database connection failed:%s', [mb_convert_encoding($errorMsg ?: 'unknown', 'UTF-8', 'UTF-8,GBK,GB2312,BIG5')])
            ];
        }

        $databases = [];
        // 不需要的数据表
        $databasesExclude = ['information_schema', 'mysql', 'performance_schema', 'sys'];
        $res              = Rdb::query("SHOW DATABASES");
        foreach ($res as $row) {
            if (!in_array($row['Database'], $databasesExclude)) {
                $databases[] = $row['Database'];
            }
        }

        return [
            'code'      => 1,
            'msg'       => '',
            'databases' => $databases,
            'pdo'       => $returnPdo ? Rdb::getPdo() : '',
        ];
    }

    /**
     * 操作成功
     * By albert  2025/04/13 15:07:30
     *
     * @param string      $msg
     * @param mixed|null  $data
     * @param int         $code
     * @param string|null $type
     * @param array       $header
     *
     * @return Response
     */
    protected function success(?string $msg = '', mixed $data = null, int $code = 1, ?string $type = null, array $header = []): Response
    {
        $response = $this->result($msg, $data, $code, $type, $header);
        return $response;
    }


    /**
     * 操作失败
     * By albert  2025/04/13 15:07:37
     *
     * @param string      $msg
     * @param mixed|null  $data
     * @param int         $code
     * @param string|null $type
     * @param array       $header
     *
     * @return Response
     */
    protected function error(?string $msg = '', mixed $data = null, ?int $code = 0, ?string $type = null, array $header = []): Response
    {
        return $this->result($msg, $data, $code, $type, $header);
    }


    /**
     * 返回封装后的 API 数据
     * By albert  2025/04/13 15:11:51
     *
     * ThinkPHP 的响应是动态解析的
     * Webman 的 Response 是 immutable 对象，更适合高并发场景
     *
     * @param string      $msg
     * @param mixed|null  $data
     * @param int         $code
     * @param string|null $contentType
     * @param array       $headers
     *
     * @return Response
     */

    public function result(string $msg, mixed $data = null, int $code = 0, ?string $contentType = null, array $headers = []): Response
    {
        $start = microtime(true);

        $responseData = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        $json     = json_encode($responseData, JSON_UNESCAPED_UNICODE);
        $response = new Response($headers['statusCode'] ?? 200, array_diff_key($headers, ['statusCode' => null]), $json);

        // 使用更高效的头信息设置方式
        $response->withHeaders([
            'Content-Type' => $contentType ?: $this->responseType ?? 'application/json',
        ]);


        return $response;
    }
}

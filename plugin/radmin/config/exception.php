<?php
/**
 * 异常处理配置
 */

use Radmin\exception\Handler;

return [
    ''=> Handler::class,
    // '' => support\exception\Handler::class,

    'exception_handler' => [
        // 不需要记录错误日志
        'dont_report' => [

        ],
        // 自定义HTTP状态码
        'status' => [
            'validate' => 400, // 验证器异常
            'jwt_token' => 401, // 认证失败
            'jwt_token_expired' => 401, // 访问令牌过期
            'jwt_refresh_token_expired' => 402, // 刷新令牌过期
            'server_error' => 500, // 服务器内部错误
            'server_error_is_response' => true, // 是否响应服务器内部错误
            'type_error' => 400, // 参数类型错误码
            'type_error_is_response' => true, // 参数类型与预期声明的参数类型不匹配
        ],
        // 自定义响应消息
        'body' => [
            'code' => 0,
            'msg' => '服务器内部异常',
            'data' => null
        ],
        /** 异常报警域名标题 */
        'domain' => [
            'dev' => 'dev-api.tinywan.com', // 开发环境
            'test' => 'test-api.tinywan.com', // 测试环境
            'pre' => 'pre-api.tinywan.com', // 预发环境
            'prod' => 'api.tinywan.com',  // 生产环境
        ],
        /** 是否生产环境 。可以通过配置文件或者数据库读取返回 eg：return config('plugin.radmin.app.env') === 'prod';*/
        'is_prod_env' => function () {
            return false;
        },
    ],

    // HTTP状态码配置
    'http_status_codes' => [
        'default'                    => 200,
        'redirect'                   => 302, // 重定向
        'parameter_error'            => 400, // 参数错误
        'unauthorized'               => 401, // 未授权
        'forbidden'                  => 403, // 禁止访问
        'not_found'                  => 404, // 未找到
        'request_timeout'            => 408, // 请求超时
        'conflict'                   => 409, // 数据冲突
        'validation_error'           => 400, // 验证错误
        'authentication_failed'      => 401, // 认证失败
        'token_expired'              => 401, // 令牌过期
        'refresh_token_expired'      => 402, // 刷新令牌过期
        'internal_server_error'      => 500, // 服务器内部错误
        'not_implemented'            => 501, // 未实现
        'bad_gateway'                => 502, // 网关错误
        'service_unavailable'        => 503, // 服务不可用
        'gateway_timeout'            => 504, // 网关超时
        'http_version_not_supported' => 505, // HTTP版本不受支持
        'respond_to_server_errors'   => true, // 是否响应服务器错误
        'type_mismatch'              => 400, // 类型不匹配
        'respond_to_type_errors'     => true, // 是否响应类型错误
    ],

    // 允许输出调试信息的请求
    'host'              => [

    ],

];

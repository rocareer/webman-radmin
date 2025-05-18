<?php

// +----------------------------------------------------------------------
// | BuildAdmin设置
// +----------------------------------------------------------------------

return [
    'cors_request_domain' => 'localhost,localhost:5173,http://localhost:5173,127.0.0.1:5173,http://127.0.0.1:5173,v10.rocareer.com',
    'user_login_captcha' => false,
    'admin_login_captcha' => false,
    'user_login_retry' => 10,
    'admin_login_retry' => 10,
    'admin_sso' => false,
    'user_sso' => false,
    'user_token_keep_time' => 259200,
    'admin_token_keep_time' => 259200,
    'open_member_center' => true,
    'module_pure_install' => true,
    'click_captcha' => [
        'mode' => [
            0 => 'text',
            1 => 'icon', ],
        'length' => 2,
        'confuse_length' => 2, ],
    'proxy_server_ip' => [ ],
    'token' => [
        'key' => [
            0 => '0CJbzXaVRuoODjc7wHAfWIyBlSp3Ervd',
            1 => 'hEx08akXnoVObZNDUj7sL6JvYG3KlMu1', ], ],
    'auto_write_admin_log' => true,
    'default_avatar' => '/static/images/avatar.png',
    'cdn_url' => '',
    'version' => 'v1.0.0',
    'api_url' => 'https://rocareer.com',
    'http_cache_ttl' => 600, ];

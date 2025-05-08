<?php


namespace support;

/**
 * Class Request
 *
 * @package support
 */
class Request extends \Webman\Http\Request
{

    public object|null $member = null;
    public string|null $token  = null;
    public array|null  $roles  = null;

}
<?php


namespace support;

/**
 * Class Request
 *
 * @package support
 */
class Request extends \Webman\Http\Request
{
    public string|null $token  = null;
    public object|null $member = null;
    public array|null  $roles  = null;
    public mixed       $upload = null;

}
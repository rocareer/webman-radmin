<?php
namespace support;
/**
 * Class Request
 *
 * @package support
 */
class Request extends \Webman\Http\Request
{
    /**
     * @var array|mixed|null
     */

    public string|null $token          = null;
    public object|null $member         = null;
    public array|null  $roles          = null;
    public mixed       $upload         = null;
    public string|null $controllerName = null;
    public string|null $role           = null;

}
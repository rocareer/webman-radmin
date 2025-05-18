<?php
/**
 * File:        MiddlewareInterface.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 15:25
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace plugin\radmin\middleware;

use Radmin\Request;
use Radmin\Response;

interface MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response;
}
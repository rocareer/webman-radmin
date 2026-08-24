<?php
/**
 * File:        MiddlewareInterface.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 15:25
 * Description:
 *
 * Copyright (c) Rocareer Team. All rights reserved.
 * Author: albert@rocareer.com
 */

namespace plugin\radmin\middleware;

use Radmin\Request;
use Radmin\Response;

interface MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response;
}
<?php
/*
 * *
 *  * +----------------------------------------------------------------------
 *  * | ${PROJECT_NAME} [ ${PROJECT_DESCRIPTION} ]
 *  * +----------------------------------------------------------------------
 *  * | File: ${FILE_NAME}
 *  * +----------------------------------------------------------------------
 *  * | Description: ${DESCRIPTION}
 *  * +----------------------------------------------------------------------
 *  * | Author: ${USER} <${USER_EMAIL}>
 *  * +----------------------------------------------------------------------
 *  * | Time: ${DATE} ${TIME}
 *  * +----------------------------------------------------------------------
 *  * | Version: ${VERSION}
 *  * +----------------------------------------------------------------------
 *  * | Copyright (c) ${YEAR} ${ORGANIZATION_NAME} All rights reserved.
 *  * +----------------------------------------------------------------------
 *  * | Licensed ( ${LICENSE_URL} )
 *  * +----------------------------------------------------------------------
 *
 */

namespace Radmin\token;

use stdClass;

interface TokenInterface
{
    /**
     * 生成Token
     * @param array $payload 附加数据
     * @return string
     */
   public function encode(array $payload = []): string;

    /**
     * 获取Token数据
     * @param string $token
     * @return stdClass
     */
    public function decode(string $token): stdClass;


    /**
     * 验证Token
     * @param string $token
     * @return stdClass
     */
    public function Verify(string $token):stdClass;



    /**
     * 废弃Token
     * @param string $token
     * @return bool
     */
    public function destroy(string $token): bool;

    /**
     * 刷新Token
     * @param string $token
     * @return string
     */
    public function refresh(string $token): string;



}

<?php
namespace support\token;

use stdClass;

interface TokenInterface
{
    /**
     * 生成Token
     * @param array $payload 附加数据
     * @return string
     */
   public function encode(array $payload = [],bool $keep=false): string;

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

<?php
namespace App\Config;

class JwtConfig
{
    // jwt秘钥
    public const SECRET_KEY = 'lifadian';

    // jwt加密方式
    public const ALG = 'HMACSHA256';

    // jwt 过期时间
    public const EXP = 60*60*24;

}

<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

function user_func(): string
{
    return 'hello';
}

function aesKey()
{
    return env('AES_KEY','salt');
}

function response()
{
    return Swoft\Context\Context::get()->getResponse();
}

function returnJson($data)
{
    return response()->withContentType(\Swoft\Http\Message\ContentType::JSON)->withData($data);
}

function encrypt($data)
{
    $data = openssl_encrypt($data, 'aes-128-ecb', base64_decode(aesKey()), OPENSSL_RAW_DATA);
    return base64_encode($data);
}

function decrypt($data) {
    $encrypted = base64_decode($data);
    return openssl_decrypt($encrypted, 'aes-128-ecb', base64_decode(aesKey()), OPENSSL_RAW_DATA);
}

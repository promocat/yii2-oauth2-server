<?php
/**
 * Created by PhpStorm.
 * User: Harry_000
 * Date: 17-5-2018
 * Time: 12:13
 *
 * Based on original by chervand (Source: https://github.com/chervand/yii2-oauth2-server)
 */

namespace NIOLAB\oauth2\components\web;

use League\OAuth2\Server\Exception\OAuthServerException;
use yii\web\HttpException;

/**
 * Class OAuthHttpException constructs {@see yii\web\HttpException} instance
 * from {@see League\OAuth2\Server\Exception\OAuthServerException}.
 */
class OauthHttpException extends HttpException
{
    /**
     * Constructor.
     * @param OAuthServerException $previous The previous exception used for the exception chaining.
     */
    public function __construct(OAuthServerException $previous)
    {
        $hint = $previous->getHint();

        parent::__construct(
            $previous->getHttpStatusCode(),
            $hint ? $previous->getMessage() . ' ' . $hint . '.' : $previous->getMessage(),
            $previous->getCode(),
            YII_DEBUG === true ? $previous : null
        );
    }
}
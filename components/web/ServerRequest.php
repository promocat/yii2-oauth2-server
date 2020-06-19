<?php
/**
 * Created by PhpStorm.
 * User: Harry_000
 * Date: 17-5-2018
 * Time: 11:58
 *
 * A PSR-7 compatible version of a request. Required for the phpleague oauth2 library.
 *
 * Based on original by chervand (Source: https://github.com/chervand/yii2-oauth2-server)
 */

namespace NIOLAB\oauth2\components\web;

use Yii;
use yii\web\HeaderCollection;
use yii\web\Request;

class ServerRequest extends \GuzzleHttp\Psr7\ServerRequest
{
    /**
     * ServerRequest constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            $request->method,
            $request->url,
            $request->headers->toArray(),
            $request->rawBody
        );
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Harry_000
 * Date: 17-5-2018
 * Time: 11:58
 *
 * A PSR-7 compatible version of a response. Required for the phpleague oauth2 library.
 *
 * Based on original by chervand (Source: https://github.com/chervand/yii2-oauth2-server)
 */

namespace NIOLAB\oauth2\components\web;

use Yii;
use yii\web\HeaderCollection;

class ServerResponse extends \GuzzleHttp\Psr7\Response {

    /**
     * Send this request as a standard Yii2 response
     */
    public function send() {
        /** @var HeaderCollection $headers */
        $headers = Yii::$app->response->headers;
        $headers->removeAll();
        foreach ($this->getHeaders() as $_header => $lines) {
            $headers->add($_header, $this->getHeaderLine($_header));
        }
        Yii::$app->response->version = $this->getProtocolVersion();
        Yii::$app->response->statusCode = $this->getStatusCode();
        Yii::$app->response->statusText = $this->getReasonPhrase();
        Yii::$app->response->content = $this->getBody();

        Yii::$app->response->send();
    }

}
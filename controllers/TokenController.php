<?php
/**
 * Created by PhpStorm.
 * User: Harry_000
 * Date: 17-5-2018
 * Time: 09:44
 */

namespace NIOLAB\oauth2\controllers;

use League\OAuth2\Server\Exception\OAuthServerException;
use NIOLAB\oauth2\components\web\OauthHttpException;
use NIOLAB\oauth2\models\AccessToken;
use NIOLAB\oauth2\Module;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\web\HttpException;

/**
 * Class TestController
 *
 * @package NIOLAB\oauth2\controllers
 *
 * @property Module module
 */
class TokenController extends ActiveController {

    /**
     * @var string
     */
    public $modelClass = AccessToken::class;

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws OauthHttpException
     */
    public function actionCreate() {
        /** @var Module $module */
        $module = $this->module;

        try {
            $request =  $module->getRequest();
            $response = $module->getResponse();

            $response = $module->getAuthorizationServer()->respondToAccessTokenRequest($request,$response);
            return Json::decode($response->getBody()->__toString());
        } catch (OAuthServerException $e) {
            throw new OAuthHttpException($e);
        } catch (\Exception $e) {
            throw new HttpException(500, 'Unable to respond to access token request.', 0,YII_DEBUG ? $e : null);
        }
    }


}
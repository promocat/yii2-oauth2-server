<?php
/**
 * Created by PhpStorm.
 * User: Harry_000
 * Date: 17-5-2018
 * Time: 09:44
 */

namespace NIOLAB\oauth2\controllers;

use common\models\User;
use League\OAuth2\Server\Exception\OAuthServerException;
use NIOLAB\oauth2\components\web\OauthHttpException;
use NIOLAB\oauth2\components\web\ServerResponse;
use NIOLAB\oauth2\models\AccessToken;
use NIOLAB\oauth2\Module;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HeaderCollection;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Class TestController
 *
 * @package NIOLAB\oauth2\controllers
 *
 * @property Module module
 */
class AuthorizeController extends Controller {

//
//    /**
//     * {@inheritdoc}
//     */
//    public function actions() {
//        return [
//            'options' => [
//                'class' => 'yii\rest\OptionsAction',
//            ],
//        ];
//    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws OauthHttpException
     * @throws \Throwable
     */
    public function actionIndex() {
        /** @var Module $module */
        $module = $this->module;

        try {
            $request =  $module->getRequest();
            $response = $module->getResponse();

            // Validate the HTTP request and return an AuthorizationRequest object.
            $authRequest = $module->getAuthorizationServer()->validateAuthorizationRequest($request);

            // The auth request object can be serialized and saved into a user's session.
            // You will probably want to redirect the user at this point to a login endpoint.

            if (Yii::$app->user->isGuest) {
                Yii::$app->user->setReturnUrl(\Yii::$app->request->url);
                return $this->redirect(Yii::$app->user->loginUrl)->send();
            }


            // Once the user has logged in set the user on the AuthorizationRequest
            /** @var User $user */
            $user = Yii::$app->user->getIdentity();
            $authRequest->setUser($user); // an instance of UserEntityInterface

            // At this point you should redirect the user to an authorization page.
            // This form will ask the user to approve the client and the scopes requested.


            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);

            /** @var ServerResponse $authResponse */
            $authResponse = $module->getAuthorizationServer()->completeAuthorizationRequest($authRequest, $response);

            $authResponse->send();

        } catch (OAuthServerException $e) {
            throw new OAuthHttpException($e);
        } catch (\Exception $e) {
            throw new HttpException(500, 'Unable to respond to access token request.', 0, YII_DEBUG ? $e : null);
        }
    }


}
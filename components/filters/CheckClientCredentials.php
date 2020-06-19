<?php
namespace NIOLAB\oauth2\components\filters;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use NIOLAB\oauth2\components\repositories\AccessTokenRepository;
use NIOLAB\oauth2\components\web\OauthHttpException;
use NIOLAB\oauth2\Module;
use Yii;
use yii\base\ActionFilter;
use yii\web\HttpException;

class CheckClientCredentials extends ActionFilter {


    private $_accessTokenRepository;


    public function beforeAction($action)
    {
        /** @var Module $module */
        $module = \Yii::$app->getModule('oauth2');

        $accessTokenRepository = $this->getAccessTokenRepository();
        $publicKeyPath = Yii::getAlias($module->publicKey);

        try {
            $server = new ResourceServer(
                $accessTokenRepository,
                $publicKeyPath
            );
            $request = $module->getRequest();
            $request = $server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $e) {
            throw new OAuthHttpException($e);
        } catch (\Exception $e) {
            throw new HttpException(500, 'Unable to validate the request.', 0, YII_DEBUG ? $e : null);
        }

        return parent::beforeAction($action);
    }

    /**
     * @return mixed
     */
    public function getAccessTokenRepository() {
        if (!$this->_accessTokenRepository instanceof AccessTokenRepositoryInterface) {
            $this->_accessTokenRepository = new AccessTokenRepository();
        }
        return $this->_accessTokenRepository;
    }

}
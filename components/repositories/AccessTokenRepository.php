<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 15-5-2018
 * Time: 16:21
 */

namespace NIOLAB\oauth2\components\repositories;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use NIOLAB\oauth2\models\AccessToken;
use NIOLAB\oauth2\models\Scope;
use yii\helpers\Json;

class AccessTokenRepository implements \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface {


    /**
     * Create a new access token
     *
     * @param ClientEntityInterface $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null) {
        $token = new AccessToken();
        $token->setClient($clientEntity);
        if (!$token->validate()) {
            throw OAuthServerException::serverError('Could not get new token: '.Json::encode($token->getErrors()));
        }

        return $token;
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity) {
        if ($accessTokenEntity instanceof  AccessToken) {
            $accessTokenEntity->expired_at = $accessTokenEntity->getExpiryDateTime()->getTimestamp();

            $accessTokenEntity->save();


            if ($accessTokenEntity->save()) {
                foreach ($accessTokenEntity->getScopes() as $scope) {
                    if ($scope instanceof Scope) {
                        $accessTokenEntity->link('relatedScopes', $scope);
                    }
                }
            }


        }
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId) {
        $token = AccessToken::find()->where(['identifier'=>$tokenId])->one();
        if ($token instanceof AccessToken) {
            $token->updateAttributes(['status' => AccessToken::STATUS_REVOKED]);
        }
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId) {
        $token = AccessToken::find()->where(['identifier'=>$tokenId])->one();
        return $token === null || $token->status == AccessToken::STATUS_REVOKED;
    }
}
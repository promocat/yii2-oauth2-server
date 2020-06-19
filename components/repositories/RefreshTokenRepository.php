<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 15-5-2018
 * Time: 16:21
 */

namespace NIOLAB\oauth2\components\repositories;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use NIOLAB\oauth2\models\RefreshToken;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface {


    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenEntityInterface
     */
    public function getNewRefreshToken() {
        return new RefreshToken();
    }

    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return RefreshTokenEntityInterface
     * @throws OAuthServerException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity) {
        if ($refreshTokenEntity instanceof  RefreshToken) {
            $refreshTokenEntity->expired_at = $refreshTokenEntity->getExpiryDateTime()->getTimestamp();
            $refreshTokenEntity->save();

            if ($refreshTokenEntity->save()) {
               return $refreshTokenEntity;
            } else {
                throw new \Exception(print_r($refreshTokenEntity->getErrors(),true));
            }
        }
        throw OAuthServerException::serverError('Refresh token failure');
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId) {
        // TODO: Implement revokeAccessToken() method.
        $token = RefreshToken::find()->where(['identifier'=>$tokenId])->one();
        if ($token instanceof RefreshToken) {
           $token->delete();
        }
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId) {
        $token = RefreshToken::find()->where(['identifier'=>$tokenId])->one();
        return $token === null;
    }
}
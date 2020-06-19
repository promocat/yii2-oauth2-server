<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 15-5-2018
 * Time: 16:21
 */

namespace NIOLAB\oauth2\components\repositories;


use frontend\models\Auth;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use NIOLAB\oauth2\models\AuthCode;
use NIOLAB\oauth2\models\Scope;

class AuthCodeRepository implements \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface {


    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode() {
        $code = new AuthCode();
        return $code;
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity) {
        if ($authCodeEntity instanceof AuthCode) {
            $authCodeEntity->expired_at = $authCodeEntity->getExpiryDateTime()->getTimestamp();

            $authCodeEntity->save();

            if ($authCodeEntity->save()) {
                foreach ($authCodeEntity->getScopes() as $scope) {
                    if ($scope instanceof Scope) {
                        $authCodeEntity->link('relatedScopes', $scope);
                    }
                }
            }
        }
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     */
    public function revokeAuthCode($codeId) {
        $code = AuthCode::find()->where(['identifier'=>$codeId])->one();
        if ($code instanceof AuthCode) {
            $code->updateAttributes(['status' => AuthCode::STATUS_REVOKED]);
        }
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId) {
        $code = AuthCode::find()->where(['identifier'=>$codeId])->one();
        return $code === null || $code->status == AuthCode::STATUS_REVOKED;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 15-5-2018
 * Time: 16:21
 */

namespace NIOLAB\oauth2\components\repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use NIOLAB\oauth2\models\Client;
use NIOLAB\oauth2\models\Scope;
use yii\db\ActiveQuery;

class ScopeRepository implements \League\OAuth2\Server\Repositories\ScopeRepositoryInterface {


    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier) {
        return Scope::find()->where(['identifier' => $identifier])->one();
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param Client $clientEntity
     * @param null|string $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null) {
        $allowedScopes = $clientEntity->getScopes(
            function (ActiveQuery $query) use ($scopes, $grantType, $userIdentifier) {
                if (empty($scopes)) {
                    $query->andWhere(['is_default' => true]);
                }
                // common and assigned to user
                $query->andWhere(['or', ['user_id' => null], ['user_id' => $userIdentifier]]);
//                // common and grant-specific
//                $query->andWhere([
//                    'or',
//                    ['grant_type' => null],
//                    ['grant_type' => Client::getGrantTypeId($grantType)]
//                ]);

            }
        );

        if (empty($scopes) === false) {
            $allowedScopes->andWhere(['in', 'identifier', $scopes]);
        }


        return $allowedScopes->all();

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 15-5-2018
 * Time: 16:46
 *
 * Client = andere applicatie die connectie maakt met ons als oauth2 server
 */

namespace NIOLAB\oauth2\models;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "oauth_access_token".
 *
 * @property int $id
 * @property int $client_id
 * @property int $user_id
 * @property string $identifier
 * @property int $type
 * @property int $created_at
 * @property int $updated_at
 * @property int $expired_at
 * @property int $status
 *
 */
class AuthCode extends ActiveRecord implements AuthCodeEntityInterface {

    const STATUS_ACTIVE = 1;
    const STATUS_REVOKED = -10;


    private $redirectUri;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%oauth_auth_code}}';
    }

    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(),[
            TimestampBehavior::class,
        ]);
    }


    /**
     * Associate a scope with the token.
     *
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopes[$scope->getIdentifier()] = $scope;
    }

    public function getRelatedScopes() {
        return $this->hasMany(Scope::class, ['id' => 'scope_id'])->viaTable('oauth_auth_code_scope', ['auth_code_id' => 'id']);
    }


    /**
     * @return ScopeEntityInterface[]|mixed
     */
    public function getScopes() {
        return $this->relatedScopes;
    }

    /**
     * Get the token's identifier.
     *
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Set the token's identifier.
     *
     * @param mixed $identifier
     */
    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    /**
     * @return string|null
     */
    public function getRedirectUri() {
        return $this->redirectUri;
    }

    /**
     * @param string $uri
     */
    public function setRedirectUri($uri) {
        $this->redirectUri = $uri;
    }

    /**
     * Get the token's expiry date time.
     *
     * @return \DateTime
     */
    public function getExpiryDateTime() {
        $dt = new \DateTime();
        $dt->setTimestamp($this->expired_at);
        return $dt;
    }

    /**
     * Set the date time when the token expires.
     *
     * @param \DateTime $dateTime
     */
    public function setExpiryDateTime(\DateTime $dateTime) {
        $this->expired_at = $dateTime->getTimestamp();
    }

    /**
     * Set the identifier of the user associated with the token.
     *
     * @param string|int|null $identifier The identifier of the user
     */
    public function setUserIdentifier($identifier) {
        $this->user_id = $identifier;
    }

    /**
     * Get the token user's identifier.
     *
     * @return string|int|null
     */
    public function getUserIdentifier() {
        return $this->user_id;
    }


    /**
     * Set the client that the token was issued to.
     *
     * @param ClientEntityInterface $client
     */
    public function setClient(ClientEntityInterface $client) {
        $this->client_id = $client->id;
    }

    /**
     * @return Client
     */
    public function getClient() {
        return $this->relatedClient;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedClient() {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['client_id'], 'required'], // identifier
            [['user_id'], 'default'],
            ['status', 'default', 'value' => static::STATUS_ACTIVE],
            ['status', 'in', 'range' => [static::STATUS_REVOKED, static::STATUS_ACTIVE]],
        ];
    }
}
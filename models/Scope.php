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

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "oauth_scope".
 *
 * @property int $id
 * @property string $identifier
 * @property string $name
 */
class Scope extends ActiveRecord implements ScopeEntityInterface {

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth_scope}}';
    }


    /**
     * Get the scope's identifier.
     *
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        return $this->getIdentifier();
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function rules()
//    {
//        return [
//            [['identifier'], 'required'],
//            [['identifier', 'name'], 'string', 'max' => 255],
//        ];
//    }

//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getOauthAccessTokenScopes()
//    {
//        return $this->hasMany(OauthAccessTokenScope::class, ['scope_id' => 'id']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getAccessTokens()
//    {
//        return $this->hasMany(OauthAccessToken::class, ['id' => 'access_token_id'])->viaTable('oauth_access_token_scope', ['scope_id' => 'id']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getOauthClientScopes()
//    {
//        return $this->hasMany(OauthClientScope::class, ['scope_id' => 'id']);
//    }
}
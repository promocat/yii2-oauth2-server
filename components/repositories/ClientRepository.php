<?php
/**
 * Created by PhpStorm.
 * User: Harry
 * Date: 15-5-2018
 * Time: 16:21
 */

namespace NIOLAB\oauth2\components\repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use NIOLAB\oauth2\models\Client;

class ClientRepository implements \League\OAuth2\Server\Repositories\ClientRepositoryInterface {

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     * @param null|string $grantType The grant type used (if sent)
     * @param null|string $clientSecret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true) {

        // @TODO check op grantType
        $client = Client::find()->where(['status'=>Client::STATUS_ACTIVE,'identifier'=>$clientIdentifier])->one();


        if ($client instanceof Client) {
            $isValidUser = !$mustValidateSecret || $client->validateSecret($clientSecret);
            if ($isValidUser) {
                return $client;
            }
        }

        return null;

    }
}
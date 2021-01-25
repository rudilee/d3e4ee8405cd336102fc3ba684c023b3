<?php

/**
 * Client Repository
 * Client repository
 * php version 7.4
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Repositories;

use DEOTransCodeChallenge\Entities\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Client repository.
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class ClientRepository implements ClientRepositoryInterface
{
    const CLIENT_NAME = 'DEOTrans Code Challenge';
    const REDIRECT_URI = 'http://localhost';

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     *
     * @return ClientEntityInterface|null
     */
    public function getClientEntity($clientIdentifier)
    {
        $client = new ClientEntity;
        $client->setIdentifier($clientIdentifier);
        $client->setName(self::CLIENT_NAME);
        $client->setRedirectUri(self::REDIRECT_URI);
        $client->setConfidential();

        return $client;
    }

    /**
     * Validate a client's secret.
     *
     * @param string      $clientIdentifier The client's identifier
     * @param null|string $clientSecret     The client's secret (if sent)
     * @param null|string $grantType        The type of grant the client is using (if sent)
     *
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        // Dummy clients
        $clients = [
            'dummyclient' => [
                'secret' => password_hash('pass123', PASSWORD_BCRYPT),
                'name' => self::CLIENT_NAME,
                'redirect_uri' => self::REDIRECT_URI,
                'is_confidential' => true
            ]
        ];

        if (!array_key_exists($clientIdentifier, $clients)) {
            return false;
        }

        $client = $clients[$clientIdentifier];
        $clientIsConfidential = $client['is_confidential'];
        $passwordIsValid = password_verify($clientSecret, $client['secret']);

        return !$clientIsConfidential && $passwordIsValid;
    }
}

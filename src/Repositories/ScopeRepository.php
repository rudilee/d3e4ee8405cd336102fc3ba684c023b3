<?php

/**
 * Scope Repository
 * Scope repository
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

use DEOTransCodeChallenge\Entities\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Scope repository.
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class ScopeRepository implements ScopeRepositoryInterface
{
    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return ScopeEntityInterface|null
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        $scopes = [
            'basic' => [
                'description' => 'Basic details about you'
            ],
            'email' => [
                'description' => 'Your email address'
            ]
        ];

        if (!array_key_exists($identifier, $scopes)) {
            return;
        }

        $scope = new ScopeEntity;
        $scope->setIdentifier($identifier);

        return $scope;
    }

    /**
     * Given a client, grant type and optional user identifier
     * validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes 
     * @param string                 $grantType 
     * @param ClientEntityInterface  $clientEntity 
     * @param null|string            $userIdentifier 
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        if ((int)$userIdentifier === 1) {
            $scope = new ScopeEntity;
            $scope->setIdentifier('email');
            $scopes[] = $scope;
        }

        return $scopes;
    }
}

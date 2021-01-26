<?php

/**
 * Client Entity
 * Client entity
 * php version 7.4
 * 
 * @category Entity
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Client entity.
 * 
 * @category Entity
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class ClientEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;

    /**
     * Set the client's name.
     *
     * @param string $name 
     * 
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Set the registered redirect URI (as a string).
     * 
     * @param string $uri 
     *
     * @return void
     */
    public function setRedirectUri(string $uri)
    {
        $this->redirectUri = $uri;
    }

    /**
     * Make the client confidential.
     *
     * @return void
     */
    public function setConfidential()
    {
        $this->isConfidential = true;
    }
}

<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ricbra\Bundle\DiscogsBundle\OAuth;

use Symfony\Component\Security\Core\SecurityContextInterface;

class HWIOauthTokenProvider implements OAuthTokenProviderInterface
{
    public $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function getToken()
    {
        $token = $this->securityContext->getToken()->getRawToken();

        return $token['oauth_token'];
    }

    public function getTokenSecret()
    {
        $token = $this->securityContext->getToken()->getRawToken();

        return $token['oauth_token_secret'];
    }
}

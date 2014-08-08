<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ricbra\Bundle\DiscogsBundle\OAuth;

interface OAuthTokenProviderInterface
{
    public function getToken();
    public function getTokenSecret();
}

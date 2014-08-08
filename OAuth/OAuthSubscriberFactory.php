<?php
/*
 * (c) Waarneembemiddeling.nl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ricbra\Bundle\DiscogsBundle\OAuth;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

class OAuthSubscriberFactory
{
    private $provider;

    public function __construct(OAuthTokenProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function factory($consumerKey, $consumerSecret)
    {
        return new Oauth1([
            'consumer_key'    => $consumerKey,
            'consumer_secret' => $consumerSecret,
            'token'           => $this->provider->getToken(),
            'token_secret'    => $this->provider->getTokenSecret()
        ]);
    }
}

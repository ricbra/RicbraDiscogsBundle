RicbraDiscogsBundle
===================

[![Latest Stable Version](https://poser.pugx.org/ricbra/discogs-bundle/v/stable.svg)](https://packagist.org/packages/ricbra/discogs-bundle) [![Total Downloads](https://poser.pugx.org/ricbra/discogs-bundle/downloads.svg)](https://packagist.org/packages/ricbra/discogs-bundle) [![Latest Unstable Version](https://poser.pugx.org/ricbra/discogs-bundle/v/unstable.svg)](https://packagist.org/packages/ricbra/discogs-bundle) [![License](https://poser.pugx.org/ricbra/discogs-bundle/license.svg)](https://packagist.org/packages/ricbra/discogs-bundle)

This bundle provides a simple integration of the "[Discogs
library](/ricbra/php-discogs-api)" into Symfony2. You can find more
information about this library on its dedicated page at
http://www.discogs.com/developers/index.html.

``` php
<?php

$discogs = $this->container->get('discogs');
````

The bundle provides a new `discogs` service that returns an instance of
`Discogs\Service`.

## Installation

    $ composer require ricbra/discogs-bundle ~1.0.0

### Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new Ricbra\Bundle\DiscogsBundle\RicbraDiscogsBundle(),
    );
}
```

### Configuration

To enable or disable the throttle:

    ricbra_discogs:
        throttle:
            enabled: false # or true

To enable OAuth you've to also use some third party library for connecting and authorization. This bundle provides
support for HWIOAuthBundle. The <code>token_provider_id</code> is the service id which provider the token and token
secret. You get this after authenticating at Discogs.

    ricbra_discogs:
        oauth:
            enabled: true
            consumer_key: _get_this_from_discogs_
            consumer_secret: _get_this_from_discogs_
            token_provider_id: ricbra_discogs.hwi_oauth_token_provider

## Basic Usage

The only thing to do is to request the `discogs` service from the container to get
an instance of `Discogs\Service` and start issuing API calls:

``` php
<?php

$discogs = $this->container->get('discogs');

$artist = $discogs->getArtist([
    'id' => 120
]);

echo $artist['name'];
```

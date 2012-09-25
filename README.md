RicbraDiscogsBundle
===================

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

To install this bundle, you'll also need [Discogs library](/ricbra/php-discogs-api)
and the [Buzz library](/kriswallsmith/Buzz). Installation depends on how your project is setup:

### Step 1: Installation using the `bin/vendors.php` method

If you're using the `bin/vendors.php` method to manage your vendor libraries,
add the following entries to the `deps` file at the root of your project file:

```
[buzz]
    git=git://github.com/kriswallsmith/Buzz.git

[discogs]
    git=git://github.com/ricbra/php-discogs-api.git

[RicbraDiscogsBundle]
    git=git://github.com/ricbra/RicbraDiscogsBundle.git
    target=bundles/Ricbra/Bundle/DiscogsBundle
```

Next, update your vendors by running:

``` bash
$ ./bin/vendors install
```

Great! Now skip down to *Step 2*.

### Step 1 (alternative): Installation with submodules

If you're managing your vendor libraries with submodules, simply add the
following submodules:

``` bash
$ git submodule add git://github.com/kriswallsmith/Buzz.git vendor/buzz
$ git submodule add git://github.com/ricbra/php-discogs-api.git vendor/discogs
$ git submodule add git://github.com/ricbra/RicbraDiscogsBundle.git vendor/bundles/Ricbra/Bundle/DiscogsBundle
```

Finally update your submodules:

``` bash
$ git submodule init
$ git submodule update
```

### Step1 (another alternative): Installation via Composer

Add this line to the `require` section of your `composer.json`:
```
"ricbra/discogs-bundle": "*"
```

run `php composer.phar update`

### Step2: Configure the autoloader
**> Skip this step if you installed bundle using Composer**

Add the following entries to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...

    'Buzz'          => __DIR__.'/../vendor/buzz/lib',
    'Discogs'       => __DIR__.'/../vendor/discogs/lib',
    'Ricbra'        => __DIR__.'/../vendor/bundles',
));
```

### Step3: Enable the bundle

Finally, enable the bundle in the kernel:

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

Congratulations! You're ready to use the Discogs libary into Symfony2!

## Basic Usage

The only thing to do is to request the `discogs` service from the container to get
an instance of `Discogs\Service` and start issuing API calls:

``` php
<?php

$discogs = $this->container->get('discogs');

$artist = $discogs->getArtist(120);

echo $artist->getName();
```

## Caching

You may take advantage of caching responses. Currently only MongoDB implementation is available. To use it you need
to grab another service called `discogs_cached_mongodb`. It will automatically create `DiscogsCached` collection
in your database. You may disable/enable caching throughout the program flow using these methods:

``` php
$discogs = $this->get('discogs_cached_mongodb');
// do something
$discogs->disableCache();
// do something
$discogs->enableCache();
```

## Configuration

By default, this bundle configures Discogs library to use the default Buzz settings. You can override these in order to
use curl for example.

Me and Discogs really appreciate it if you supply your own unique identifier (will be send as user agent header).
Furthermore the default items per request is 50. Discogs API also states that it doesn't limit you in requests amount,
but throttles them in time - 1 request per second. The library has throttling mechanism implemented and enabled by default,
so you won't get into request rejecting situation using free access. A typically config would look like this:

``` yaml
ricbra_discogs:
    identifier: "VendorAcmeBundle/0.1 +http://yourgreatwebsite.com"
    items_per_page: 100
    throttle: true
```

Also, if your required configuration differs from the default one you may specify your own service inside the `services.yml` file 
with all necessary parameters set:

``` yaml
acme_discogs:
    class: Discogs\Service
    arguments:
        - @ricbra_discogs.client
        - %ricbra_discogs.items_per_page%
        - %ricbra_discogs.throttle%
    calls:
        - [setResponseTransformer, [@discogs_response_transformer_hash]]
        - [setCacher, [@discogs_cacher_mongodb]]
```

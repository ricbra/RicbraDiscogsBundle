RicbraDiscogsBundle
===================

This bundle provides a simple integration of the "[Discogs
library](/ricbra/php-discogs-api)" into Symfony2. You can find more
information about this library on its dedicated page at
https://github.com/ricbra/php-discogs-api.

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

[SensioBuzzBundle]
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

### Step2: Configure the autoloader

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

The only thing to do is to request the `buzz` service from the container to get
an instance of `Buzz\Browser` and start issuing HTTP requests:

``` php
<?php

$discogs = $this->container->get('discogs');

$artist = $discogs->getArtist(120);

echo $artist->getName();
```

## Configuration

By default, this bundle configures Discogs library to use the default Buzz settings. You can override these in order to
use curl for example.

Me and Discogs really appreciate it if you supply your own unique identifier (will be send as user agent header).
Furthermore the default items per request is 50. A typically config would look like this:

``` yaml
ricbra_discogs:
    identifier: "VendorAcmeBundle/0.1 +http://yourgreatwebsite.com"
    items_per_page: 100
```

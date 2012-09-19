<?php
namespace Ricbra\Bundle\DiscogsBundle\Cacher;

use Discogs\CacherInterface;
use Ricbra\Bundle\DiscogsBundle\Document\Cached;
// can't use DocumentManager here as it may not be available in project

/**
 * @author Artem Komarov <i.linker@gmail.com>
 */
class MongoDB implements CacherInterface
{
    /**
     * @var mixed
     */
    protected $dm;

    /**
     * @var int
     */
    protected $cacheTtl;

    /**
     * @var bool
     */
    protected $isOperational = false;

    public function __construct($dm, $cacheTtl = 86400)
    {
        $this->isOperational = is_object($dm);
        $this->dm = $dm;
        $this->cacheTtl = (int)$cacheTtl;
    }

    /**
     * Persist request and response into DB
     *
     * @param string $key
     * @param string $json
     * @return null
     */
    public function persist($key, $json)
    {
        if (!$this->isOperational()) {
            return;
        }

        $cached = new Cached();
        $cached->setRequest($key);
        $cached->setResponse($json);

        $this->dm->persist($cached);
        $this->dm->flush();
    }

    /**
     * Retrieve response by request
     *
     * @param string $key
     * @return string|bool
     */
    public function retrieve($key)
    {
        if (!$this->isOperational()) {
            return false;
        }

        $response = false;
        $repository = $this->dm->getRepository('RicbraDiscogsBundle:Cached');
        /* @var $cached Cached */
        $cached = $repository->findOneBy(array('request' => $key));

        if ($cached) {
            if ($cached->getCachedAt()->getTimestamp() + $this->cacheTtl < time()) {
                $this->dm->remove($cached);
                $this->dm->flush();
            } else {
                $response = $cached->getResponse();
            }
        }

        return $response;
    }

    public function isOperational()
    {
        return $this->isOperational;
    }
}

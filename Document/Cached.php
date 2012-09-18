<?php
namespace Ricbra\Bundle\DiscogsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="DiscogsCached")
 */
class Cached
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex
     */
    protected $request;

    /**
     * @MongoDB\String
     */
    protected $response;

    /**
     * @MongoDB\Date
     */
    protected $cachedAt;

    public function setCachedAt(\DateTime $cachedAt)
    {
        $this->cachedAt = $cachedAt;
    }

    /**
     * @return \DateTime
     */
    public function getCachedAt()
    {
        return $this->cachedAt;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @MongoDB\PrePersist
     */
    public function prePersist()
    {
        $this->cachedAt = new \DateTime();
    }
}

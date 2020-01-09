<?php
namespace Idealogica\OrmHelper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\SimpleCache\CacheInterface;

/**
 * Class AbstractRepository
 * @package Idealogica\OrmHelper
 */
class AbstractRepository extends EntityRepository
{
    const SORT_ORDER_ASC = 'asc';

    const SORT_ORDER_DESC = 'desc';

    /**
     * @var null|AbstractRepositoryFactory
     */
    protected $repositoryFactory = null;

    /**
     * @var null|CacheInterface
     */
    protected $cache = null;

    /**
     * AbstractRepository constructor.
     *
     * @param EntityManager $em
     * @param ClassMetadata $class
     * @param AbstractRepositoryFactory $repositoryFactory
     * @param CacheInterface $cache
     */
    public function __construct(
        EntityManager $em,
        ClassMetadata $class,
        AbstractRepositoryFactory $repositoryFactory,
        CacheInterface $cache = null
    ) {
        parent::__construct($em, $class);
        $this->repositoryFactory = $repositoryFactory;
        $this->cache = $cache;
    }

    /**
     * @return AbstractRepositoryFactory
     */
    protected function getRepositoryFactory()
    {
        return $this->repositoryFactory;
    }

    /**
     * @return null|CacheInterface
     */
    protected function getCache()
    {
        return $this->cache;
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function cacheGet(string $key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null|int|\DateInterval $ttl
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function cacheSet(string $key, $value, $ttl = null)
    {
        return $this->cache->set($key, $value, $ttl);
    }
}

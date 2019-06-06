<?php
namespace Idealogica\OrmHelper;

use Doctrine\ORM\EntityManager;

/**
 * Class AbstractRepositoryFactory
 * @package Idealogica\OrmHelper
 */
class AbstractRepositoryFactory
{
    /**
     * @var null|EntityManager
     */
    protected $em = null;

    /**
     * RepositoryFactory constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager|null
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}

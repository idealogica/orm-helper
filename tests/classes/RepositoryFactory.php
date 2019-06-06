<?php
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Idealogica\OrmHelper\AbstractRepositoryFactory;

/**
 * Class RepositoryFactory
 * @package Proxy
 */
class RepositoryFactory extends AbstractRepositoryFactory
{
    /**
     * @return ObjectRepository|EntityRepository|TestEntityRepository
     */
    public function getTestEntityRepository()
    {
        return $this->em->getRepository(TestEntity::class);
    }
}

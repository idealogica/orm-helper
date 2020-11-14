<?php
namespace Idealogica\OrmHelper\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory as RepositoryFactoryInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class ClosureRepositoryFactory
 * @package Idealogica\OrmHelper\Doctrine
 */
class ClosureRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * @var null|\Closure
     */
    protected $getRepository;

    /**
     * ClosureRepositoryFactory constructor.
     *
     * @param \Closure $getRepository
     */
    public function __construct(\Closure $getRepository)
    {
        $this->getRepository = $getRepository;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param string $entityName
     *
     * @return ObjectRepository
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $metadata = $entityManager->getClassMetadata($entityName);
        return $this->getRepository->__invoke($entityName, $entityManager, $metadata);
    }
}

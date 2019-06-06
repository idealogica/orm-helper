<?php
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Idealogica\OrmHelper\Doctrine\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * Class OrmHelperTest
 */
class OrmHelperTest extends TestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function testEntityManager()
    {
        $modelsPath = __DIR__ . '/models';
        $logFilePath = __DIR__ . '/log.txt';
        $dbPath = __DIR__ . '/test.sqlite';
        $cachePath = __DIR__ . '/cache';

        $repositoryFactory = null;
        $em = EntityManager::createFromParams(
            function ($name, DoctrineEntityManager $em, ClassMetadata $metadata) use (&$repositoryFactory) {
                $className = $name . 'Repository';
                return new $className($em, $metadata, $repositoryFactory);
            },
            $modelsPath,
            [
                'driver' => 'pdo_sqlite',
                'path' => $dbPath,
            ],
            $logFilePath,
            $cachePath,
            true
        );
        $repositoryFactory = new RepositoryFactory($em);
        $testEntities = $repositoryFactory->getTestEntityRepository()->findAll();
        self::assertCount(2, $testEntities);
        self::assertInstanceOf(TestEntity::class, $testEntities[0]);
        self::assertInstanceOf(TestEntity::class, $testEntities[1]);
        self::assertFileExists($logFilePath);
        unlink($logFilePath);
    }
}

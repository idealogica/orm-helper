<?php
namespace Idealogica\OrmHelper\Doctrine;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\Tools\Setup;
use Idealogica\LogX;

/**
 * Class EntityManager
 * @package Idealogica\OrmHelper\Doctrine
 */
class EntityManager extends DoctrineEntityManager
{
    /**
     * @param \CLosure $repositoryFactory
     * @param string $modelsPath
     * @param array $dbConfig
     * @param string $dbLogPath
     * @param string $cachePath
     * @param bool $debugMode
     *
     * @return DoctrineEntityManager
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public static function createFromParams(
        \Closure $repositoryFactory,
        $modelsPath,
        $dbConfig,
        $dbLogPath,
        $cachePath,
        $debugMode
    ) {
        $cache = $debugMode ? null : new FilesystemCache($cachePath);
        $config = Setup::createConfiguration($debugMode, null, $cache);
        $config->setProxyDir($cachePath);
        $config->setAutoGenerateProxyClasses(
            $debugMode ?
                AbstractProxyFactory::AUTOGENERATE_ALWAYS :
                AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS
        );
        // disabled due to bad performance
        // $regionsConfig = new RegionsConfiguration();
        // $factory = new DefaultCacheFactory($regionsConfig, $cache);
        // $factory->setFileLockRegionDirectory($cachePath);
        // $config->setSecondLevelCacheEnabled(true);
        // $config->getSecondLevelCacheConfiguration()->setCacheFactory($factory);
        $config->setMetadataDriverImpl(new StaticPHPDriver($modelsPath));
        $config->setNamingStrategy(new NamingStrategy());
        $config->setRepositoryFactory(new ClosureRepositoryFactory($repositoryFactory));
        if ($debugMode) {
            $config->setSQLLogger(new SqlLogger(new LogX($dbLogPath)));
        }
        return EntityManager::create($dbConfig, $config);
    }
}

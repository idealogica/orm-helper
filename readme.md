# OrmHelper - Collection of classes to simplify Doctrine ORM everyday usage

## 1. Installation

```
composer require idealogica/orm-helper:~1.0.0
```

## 2. Basic example

```
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
```

## 3. License

OrmHelper is licensed under a [MIT License](https://opensource.org/licenses/MIT).

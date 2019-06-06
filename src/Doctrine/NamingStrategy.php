<?php
namespace Idealogica\OrmHelper\Doctrine;

use Doctrine\ORM\Mapping\NamingStrategy as NamingStrategyInterface;

/**
 * Class NamingStrategy
 * @package Idealogica\OrmHelper\Doctrine
 */
class NamingStrategy implements NamingStrategyInterface
{
    /**
     * @param string $className
     *
     * @return bool|string
     */
    public function classToTableName($className)
    {
        if (strpos($className, '\\') !== false) {
            $className = substr($className, strrpos($className, '\\') + 1);
        }
        return ucfirst($className);
    }

    /**
     * @param string $propertyName
     * @param null|string $className
     *
     * @return string
     */
    public function propertyToColumnName($propertyName, $className = null)
    {
        return $propertyName;
    }

    /**
     * @param string $propertyName
     * @param string $embeddedColumnName
     * @param null|string $className
     * @param null|string $embeddedClassName
     *
     * @return string
     */
    public function embeddedFieldToColumnName($propertyName, $embeddedColumnName, $className = null, $embeddedClassName = null)
    {
        return lcfirst($propertyName) . ucfirst($embeddedColumnName);
    }

    /**
     * @return string
     */
    public function referenceColumnName()
    {
        return 'id';
    }

    /**
     * @param string $propertyName
     * @param null|string $className
     *
     * @return string
     */
    public function joinColumnName($propertyName, $className = null)
    {
        return lcfirst($propertyName) . ucfirst($this->referenceColumnName());
    }

    /**
     * @param string $sourceEntity
     * @param string $targetEntity
     * @param null|string $propertyName
     *
     * @return string
     */
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null)
    {
        return $this->classToTableName($sourceEntity) . $this->classToTableName($targetEntity);
    }

    /**
     * @param string $entityName
     * @param null|string $referencedColumnName
     *
     * @return string
     */
    public function joinKeyColumnName($entityName, $referencedColumnName = null)
    {
        return lcfirst($this->classToTableName($entityName)) . ucfirst($referencedColumnName ?: $this->referenceColumnName());
    }
}

<?php
namespace Idealogica\OrmHelper\Object;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Idealogica\OrmHelper\Exception;
use Respect\Validation\Validator;

/**
 * Class AbstractTimestampObject
 * @package Idealogica\OrmHelper
 */
abstract class AbstractTimestampObject extends AbstractObject
{
    const FIELD_INSERTED_ON = 'insertedOn';

    const FIELD_UPDATED_ON = 'updatedOn';

    /**
     * @var null|\DateTime
     */
    protected $insertedOn = null;

    /**
     * @var null|\DateTime
     */
    protected $updatedOn = null;

    /**
     * @param ClassMetadata $metadata
     *
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    protected static function init(ClassMetadata $metadata)
    {
        parent::init($metadata);
        $metadata->mapField([
            self::PARAM_FIELD_NAME => self::FIELD_INSERTED_ON,
            self::PARAM_TYPE => self::TYPE_DATETIME,
            self::PARAM_NULLABLE => false,
            self::PARAM_VALIDATOR => Validator::date()
        ]);
        $metadata->mapField([
            self::PARAM_FIELD_NAME => self::FIELD_UPDATED_ON,
            self::PARAM_TYPE => self::TYPE_DATETIME,
            self::PARAM_NULLABLE => false,
            self::PARAM_VALIDATOR => Validator::date()
        ]);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param array $errors
     *
     * @throws Exception\ValidationException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function prePersist(LifecycleEventArgs $args, array $errors = [])
    {
        if (!$this->getInsertedOn()) {
            $this->setInsertedOn(new \DateTime());
        }
        if (!$this->getUpdatedOn()) {
            $this->setUpdatedOn(new \DateTime());
        }
        parent::prePersist($args, $errors);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param array $errors
     *
     * @throws Exception\ValidationException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function preUpdate(LifecycleEventArgs $args, array $errors = [])
    {
        if (!$this->getInsertedOn()) {
            $this->setInsertedOn(new \DateTime());
        }
        $this->setUpdatedOn(new \DateTime());
        parent::preUpdate($args, $errors);
    }

    /**
     * @return \DateTime|null
     */
    public function getInsertedOn()
    {
        return $this->insertedOn;
    }

    /**
     * @param \DateTime|null $insertedOn
     *
     * @return AbstractTimestampObject
     */
    public function setInsertedOn(\DateTime $insertedOn = null): self
    {
        $this->insertedOn = $insertedOn;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param \DateTime|null $updatedOn
     *
     * @return AbstractTimestampObject
     */
    public function setUpdatedOn(\DateTime $updatedOn = null): self
    {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @param array $data
     * @param bool $safeAssign
     *
     * @return $this
     * @throws \ReflectionException
     */
    public function assign(array $data, bool $safeAssign = true)
    {
        if ($safeAssign) {
            unset($data[self::FIELD_INSERTED_ON]);
            unset($data[self::FIELD_UPDATED_ON]);
        }
        parent::assign($data, $safeAssign);
        return $this;
    }
}

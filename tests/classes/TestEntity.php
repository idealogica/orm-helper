<?php
use Doctrine\ORM\Mapping\ClassMetadata;
use Idealogica\OrmHelper\Entity\AbstractTimestampEntity;
use Respect\Validation\Validator;

/**
 * Class TestEntity
 */
class TestEntity extends AbstractTimestampEntity
{
    const FIELD_INT_PROPERTY = 'intProperty';

    const FIELD_STRING_PROPERTY = 'stringProperty';

    /**
     * @var int
     */
    protected $intProperty;

    /**
     * @var bool
     */
    protected $stringProperty;

    /**
     * @param ClassMetadata $metadata
     *
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public static function loadMetadata(ClassMetadata $metadata)
    {
        self::init($metadata);
        $metadata->mapField([
            self::PARAM_FIELD_NAME => self::FIELD_INT_PROPERTY,
            self::PARAM_TYPE => self::TYPE_INTEGER,
            self::PARAM_NULLABLE => false,
            self::PARAM_VALIDATOR => Validator::intType()
        ]);
        $metadata->mapField([
            self::PARAM_FIELD_NAME => self::FIELD_STRING_PROPERTY,
            self::PARAM_TYPE => self::TYPE_STRING,
            self::PARAM_NULLABLE => false,
            self::PARAM_VALIDATOR => Validator::stringType()->length(3, 254)
        ]);
    }

    /**
     * @return int
     */
    public function getIntProperty(): int
    {
        return $this->intProperty;
    }

    /**
     * @param int $intProperty
     *
     * @return TestEntity
     */
    public function setIntProperty(int $intProperty): TestEntity
    {
        $this->intProperty = $intProperty;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStringProperty(): bool
    {
        return $this->stringProperty;
    }

    /**
     * @param bool $stringProperty
     *
     * @return TestEntity
     */
    public function setStringProperty(bool $stringProperty): TestEntity
    {
        $this->stringProperty = $stringProperty;
        return $this;
    }
}

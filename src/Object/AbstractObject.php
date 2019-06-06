<?php
namespace Idealogica\OrmHelper\Object;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Idealogica\OrmHelper\Exception\ValidationException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Respect\Validation\Validator;
use function Idealogica\OrmHelper\mixedToString;

/**
 * Class AbstractObject
 * @package Idealogica\OrmHelper
 */
abstract class AbstractObject
{
    const FIELD_ID = 'id';

    const PARAM_FIELD_NAME = 'fieldName';

    const PARAM_TYPE = 'type';

    const PARAM_NULLABLE = 'nullable';

    const PARAM_VALIDATOR = 'validator';

    const PARAM_TARGET_ENTITY = 'targetEntity';

    const PARAM_INVERSED_BY = 'inversedBy';

    const PARAM_MAPPED_BY = 'mappedBy';

    const PARAM_ID = 'id';

    const TYPE_INTEGER = 'integer';

    const TYPE_BOOLEAN = 'boolean';

    const TYPE_STRING = 'string';

    const TYPE_DATETIME = 'datetime';

    const TYPE_FLOAT = 'float';

    /**
     * @var null|int
     */
    protected $id;

    /**
     * @return string
     */
    public static function getTableName()
    {
        $classNameWithNamespace = get_called_class();
        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    protected static function init(ClassMetadata $metadata)
    {
        // $metadata->enableCache(['usage' => ClassMetadataInfo::CACHE_USAGE_READ_WRITE]);
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_IDENTITY);
        $metadata->mapField([
            self::PARAM_FIELD_NAME => self::FIELD_ID,
            self::PARAM_TYPE => self::TYPE_INTEGER,
            self::PARAM_ID => true,
            self::PARAM_NULLABLE => true,
            self::PARAM_VALIDATOR => Validator::intType()
        ]);
        $metadata->addLifecycleCallback('prePersist', Events::prePersist);
        $metadata->addLifecycleCallback('preUpdate', Events::preUpdate);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param array $errors
     *
     * @throws ValidationException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function prePersist(LifecycleEventArgs $args, array $errors = [])
    {
        $this->validate($args, $errors);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param array $errors
     *
     * @throws ValidationException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function preUpdate(LifecycleEventArgs $args, array $errors = [])
    {
        $this->validate($args, $errors);
    }

    /**
     * @param LifecycleEventArgs $args
     * @param array $errors
     *
     * @throws ValidationException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function validate(LifecycleEventArgs $args, array $errors = [])
    {
        $metadata = $args->getEntityManager()->getClassMetadata(static::class);
        foreach ($metadata->getFieldNames() as $name) {
            $mapping = $metadata->getFieldMapping($name);
            if (empty($mapping[self::PARAM_VALIDATOR]) || !$mapping[self::PARAM_VALIDATOR] instanceof Validatable) {
                continue;
            }
            if (!empty($mapping[self::PARAM_NULLABLE]) && $this->{$name} === null) {
                continue;
            }
            $validator = $mapping[self::PARAM_VALIDATOR];
            /**
             * @var Validator $validator
             */
            $validator->setName($name);
            try {
                $validator->assert($this->{$name});
            } catch (NestedValidationException $e) {
                foreach ($e->getMessages() as $message) {
                    $errors[$name] = $message . ' (' . mixedToString($this->{$name}) . ')';
                }
            }
        }
        if ($errors) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $res = [];
        $properties = get_class_vars(get_called_class());
        foreach ($properties as $propertyName => $v) {
            $res[$propertyName] = $this->{$propertyName};
        }
        return $res;
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
            unset($data[self::PARAM_ID]);
        }
        $calledClass = get_called_class();
        $ref = new \ReflectionClass($calledClass);
        $properties = array_filter($ref->getProperties(), function ($property) use ($calledClass) {
            return $property->class === $calledClass;
        });
        foreach ($properties as $property) {
            if (isset($data[$property->name])) {
                $this->{$property->name} = $data[$property->name];
            }
        }
        return $this;
    }

    /**
     * @param string $data
     * @param bool $safeAssign
     *
     * @return $this
     * @throws \ReflectionException
     */
    public function assignJson(string $data, bool $safeAssign = true)
    {
        $array = json_decode($data, true);
        if ($array) {
            $this->assign($array, $safeAssign);
        }
        return $this;
    }
}

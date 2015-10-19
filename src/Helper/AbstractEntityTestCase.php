<?php
/**
 * Developer: Anton Chernik
 * E-mail: anton@chernik.info
 * Date: 13.09.2015
 * Time: 22:42
 */

namespace Core2bMock\Helper;


abstract class AbstractEntityTestCase extends \PHPUnit_Framework_TestCase {


    protected abstract function getObject();


    protected $class = null;

    /**
     * Return class
     * @return null
     * @throws \Exception
     */
    public function getClass()
    {
        if (!$this->class) {
            throw new \Exception('Class not set');
        }
        return $this->class;
    }


    /**
     * Return object protected property value
     * @param $object
     * @param $propertyName
     * @return mixed
     */
    protected function getObjectPropertyValue($object, $propertyName)
    {
        $reflectionObject = new \ReflectionObject($object);
        $property = $reflectionObject->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }


    const PROPERTY_TYPE_MIXED = 'mixed';
    const PROPERTY_TYPE_ARRAY = 'array';
    const PROPERTY_TYPE_JSON_OBJECT = 'json_object';


    /**
     * Run property setter and getter test
     * @param $propertyName
     * @param $value
     * @param string $type
     * @throws \Exception
     */
    public function runPropertyTest($propertyName, $value, $type = 'mixed')
    {
        $object = $this->getObject();
        $setterName = 'set'.ucfirst($propertyName);
        $getterName = 'get'.ucfirst($propertyName);
        $object->$setterName($value);
        switch ($type) {
            case self::PROPERTY_TYPE_JSON_OBJECT:
                $this->assertSame(json_encode($value), $this->getObjectPropertyValue($object, $propertyName), 'Wrong value set');
                $this->assertSame($value, $object->$getterName(), 'Wrong value returned');
                break;
            case self::PROPERTY_TYPE_ARRAY:
            case self::PROPERTY_TYPE_MIXED:
                $this->assertSame($value, $this->getObjectPropertyValue($object, $propertyName), 'Wrong value set');
                $this->assertSame($value, $object->$getterName(), 'Wrong value returned');
                break;
            default:
                throw new \Exception('Invalid type "'.$type.'". Only '.implode(' ', [self::PROPERTY_TYPE_ARRAY, self::PROPERTY_TYPE_JSON_OBJECT, self::PROPERTY_TYPE_MIXED]).' allowed');
                break;
        }

    }

    /**
     * Run available method test
     * @param $requiredMethods
     */
    public function runAvailableMethodTest($requiredMethods)
    {
        $methodList = get_class_methods($this->getClass());
        $this->assertEquals(sizeof($methodList), sizeof($requiredMethods), 'Methods quantity not equals');
        foreach ($requiredMethods as $methodName) {
            $this->assertTrue(in_array($methodName, $methodList), 'Class does not have method '.$methodName);
        }
    }




}
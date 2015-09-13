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
     * @param $propertyName
     * @return mixed
     */
    protected function getObjectPropertyValue($propertyName)
    {
        $object = $this->getObject();
        $reflectionObject = new \ReflectionObject($object);
        $property = $reflectionObject->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }


    /**
     * Run property setter and getter test
     * @param $propertyName
     * @param $value
     */
    public function runPropertyTest($propertyName, $value)
    {
        $object = $this->getObject();
        $setterName = 'set'.ucfirst($propertyName);
        $getterName = 'get'.ucfirst($propertyName);
        $object->$setterName($value);
        $this->assertSame($value, $this->getObjectPropertyValue($object, $propertyName), 'Wrong value set');
        $this->assertSame($value, $object->$getterName(), 'Wrong value returned');
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
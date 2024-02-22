<?php

namespace Core;

use InvalidArgumentException;
use Sh1ne\MySqlBot\Core\ServiceContainer;
use PHPUnit\Framework\TestCase;

class ServiceContainerTest extends TestCase
{

    public function testInstance()
    {
        $instance1 = ServiceContainer::instance();
        $instance2 = ServiceContainer::instance();

        $this->assertInstanceOf(ServiceContainer::class, $instance1);
        $this->assertSame($instance1, $instance2);
    }

    public function testSingletonByInstance()
    {
        $serviceContainer = new ServiceContainer();

        $expected = new MyClass();

        $serviceContainer->singletonByInstance(MyInterface::class, $expected);

        $actual = $serviceContainer->get(MyInterface::class);

        $this->assertSame($expected, $actual);
    }

    public function testSingleton()
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->singleton(MyInterface::class, MyClass::class);

        $actual1 = $serviceContainer->get(MyInterface::class);
        $actual2 = $serviceContainer->get(MyInterface::class);

        $this->assertInstanceOf(MyClass::class, $actual1);
        $this->assertSame($actual1, $actual2);
        $this->assertEquals($actual1, $actual2);
    }

    public function testSingletonSameClass()
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->singleton(MyClass::class, MyClass::class);

        $actual1 = $serviceContainer->get(MyClass::class);
        $actual2 = $serviceContainer->get(MyClass::class);

        $this->assertInstanceOf(MyClass::class, $actual1);
        $this->assertSame($actual1, $actual2);
        $this->assertEquals($actual1, $actual2);
    }

    public function testRegister()
    {
        $serviceContainer = new ServiceContainer();

        $serviceContainer->register(MyInterface::class, MyClass::class);

        $actual1 = $serviceContainer->get(MyInterface::class);
        $actual2 = $serviceContainer->get(MyInterface::class);

        $this->assertInstanceOf(MyClass::class, $actual1);
        $this->assertNotSame($actual1, $actual2);
    }

    public function testGetInstantiatedObject()
    {
        $serviceContainer = new ServiceContainer();

        $actual = $serviceContainer->get(MyClass::class);

        $this->assertInstanceOf(MyClass::class, $actual);
    }

    public function testGetClassNotExist()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('Class "test" does not exist');

        $serviceContainer = new ServiceContainer();

        $serviceContainer->get('test');
    }

    public function testGetInterfaceCannotBeInstantiated()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage("Interface 'Core\MyInterface' cannot be instantiated");

        $serviceContainer = new ServiceContainer();

        $serviceContainer->get(MyInterface::class);
    }

    public function testGetAbstractClassCannotBeInstantiated()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage("Class 'Core\MyAbstractClass' cannot be instantiated as it is abstract");

        $serviceContainer = new ServiceContainer();

        $serviceContainer->get(MyAbstractClass::class);
    }

    public function testGetInterfaceCannotBeInstantiatedAsParameter()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage("Interface 'Core\MyInterface' cannot be instantiated");

        $serviceContainer = new ServiceContainer();

        $serviceContainer->get(MyClassWithConstructor::class);
    }

    public function testGetWithInterfaceInConstructor()
    {
        $serviceContainer = new ServiceContainer();

        $myClass = new MyClass();

        $serviceContainer->singletonByInstance(MyInterface::class, $myClass);

        $actual = $serviceContainer->get(MyClassWithConstructor::class);

        $this->assertEquals($myClass, $actual->getMyInterface());
    }

}

abstract class MyAbstractClass
{

}

interface MyInterface
{

}

class MyClass implements MyInterface
{

}

class MyClassWithConstructor implements MyInterface
{

    private MyInterface $myInterface;

    public function __construct(MyInterface $myInterface)
    {
        $this->myInterface = $myInterface;
    }

    public function getMyInterface() : MyInterface
    {
        return $this->myInterface;
    }

}

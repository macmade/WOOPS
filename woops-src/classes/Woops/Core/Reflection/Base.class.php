<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * WOOPS PHP error exception class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
abstract class Woops_Core_Reflection_Base extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    private static $_instances          = array();
    
    /**
     * 
     */
    private static $_nbInstancesByClass = array();
    
    /**
     * 
     */
    private static $_nbInstances        = 0;
    
    /**
     * 
     */
    private $_instanceName              = '';
    
    /**
     * 
     */
    private $_reflectorClass            = '';
    
    /**
     * 
     */
    private $_hasParameters             = false;
    
    /**
     * 
     */
    private $_parameters                = array();
    
    /**
     * 
     */
    private $_hasExtension              = false;
    
    /**
     * 
     */
    private $_extension                 = NULL;
    
    /**
     * 
     */
    private $_hasDeclaringClass         = false;
    
    /**
     * 
     */
    private $_declaringClass            = NULL;
    
    /**
     * 
     */
    private $_hasDeclaringFunction      = false;
    
    /**
     * 
     */
    private $_declaringFunction         = NULL;
    
    /**
     * 
     */
    private $_hasClass                  = false;
    
    /**
     * 
     */
    private $_class                     = NULL;
    
    /**
     * 
     */
    private $_hasFunctions              = false;
    
    /**
     * 
     */
    private $_functions                 = array();
    
    /**
     * 
     */
    private $_hasClasses                = false;
    
    /**
     * 
     */
    private $_classes                   = array();
    
    /**
     * 
     */
    private $_hasMethods                = false;
    
    /**
     * 
     */
    private $_methods                   = array();
    
    /**
     * 
     */
    private $_abstractMethods           = array();
    
    /**
     * 
     */
    private $_finalMethods              = array();
    
    /**
     * 
     */
    private $_privateMethods            = array();
    
    /**
     * 
     */
    private $_protectedMethods          = array();
    
    /**
     * 
     */
    private $_publicMethods             = array();
    
    /**
     * 
     */
    private $_staticMethods             = array();
    
    /**
     * 
     */
    private $_hasConstructor            = false;
    
    /**
     * 
     */
    private $_constructor               = NULL;
    
    /**
     * 
     */
    private $_hasInterfaces             = false;
    
    /**
     * 
     */
    private $_interfaces                = array();
    
    /**
     * 
     */
    private $_hasProperties             = false;
    
    /**
     * 
     */
    private $_properties                = array();
    
    /**
     * 
     */
    private $_privateProperties         = array();
    
    /**
     * 
     */
    private $_protectedProperties       = array();
    
    /**
     * 
     */
    private $_publicProperties          = array();
    
    /**
     * 
     */
    private $_staticProperties          = array();
    
    /**
     * 
     */
    private $_hasParentClass            = false;
    
    /**
     * 
     */
    private $_parentClass               = NULL;
    
    /**
     * 
     */
    protected $_reflector               = NULL;
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return void
     */
    final protected function __construct( Reflector $reflector )
    {
        $this->_reflector = $reflector;
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    final public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * 
     */
    final public function __get( $name )
    {
        return $this->_reflector->$name;
    }
    
    /**
     * 
     */
    final public function __set( $name, $value )
    {
        $this->_reflector->$name = $value;
    }
    
    /**
     * 
     */
    final public function __isset( $name )
    {
        return isset( $this->_reflector->$name );
    }
    
    /**
     * 
     */
    final public function __unset( $name )
    {
        unset( $this->_reflector->$name );
    }
    
    /**
     * 
     */
    final public function __call( $name, array $args = array() )
    {
        if( ( $name === 'getParameters' || $name === 'getExtension' )
            && ( $this->_reflectorClass === 'ReflectionFunction' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        if( ( $name === 'getDeclaringClass' )
            && ( $this->_reflectorClass === 'ReflectionProperty' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        if( ( $name === 'getParameters' || $name === 'getDeclaringClass' || $name === 'getExtension' )
            && ( $this->_reflectorClass === 'ReflectionMethod' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        if( ( $name === 'getDeclaringFunction' || $name === 'getDeclaringClass' || $name === 'getClass' )
            && ( $this->_reflectorClass === 'ReflectionParameter' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        if( ( $name === 'getFunctions' || $name === 'getClasses' )
            && ( $this->_reflectorClass === 'ReflectionExtension' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        if( ( $name === 'getMethod' || $name === 'getMethods' || $name === 'getConstructor' || $name === 'getInterfaces' || $name === 'getProperty' || $name === 'getProperties' || $name === 'getParentClass' || $name === 'isSubclassOf' || $name === 'getExtension' )
            && ( $this->_reflectorClass === 'ReflectionClass' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        if( ( $name === 'getMethod' || $name === 'getMethods' || $name === 'getConstructor' || $name === 'getInterfaces' || $name === 'getProperty' || $name === 'getProperties' || $name === 'getParentClass' || $name === 'isSubclassOf' || $name === 'getExtension' )
            && ( $this->_reflectorClass === 'ReflectionObject' )
        ) {
            return $this->_callMethod( $this, '_' . $name, $args );
        }
        
        return $this->_callMethod( $this->_reflector, $name, $args );
    }
    
    /**
     * 
     */
    final public function __toString()
    {
        return ( string )$this->_reflector;
    }
    
    /**
     * 
     */
    final private static function _newReflector( $reflectorClass, array $args )
    {
        $argsCount = count( $args );
        
        switch( $argsCount ) {
            
            case 1:
                
                return new $reflectorClass( $args[ 0 ] );
                break;
            
            case 2:
                
                return new $reflectorClass( $args[ 0 ], $args[ 1 ] );
                break;
        }
    }
    
    /**
     * 
     */
    final protected static function _getInstance( $childClass, $reflectorClass, array $args )
    {
        if( is_object( $args[ 0 ] ) ) {
            
            $hash         = spl_object_hash( $args[ 0 ] );
            $instanceName = ( isset( $args[ 1 ] ) ) ? $hash . '::' . $args[ 1 ] : $hash;
            
        } else {
            
            $instanceName = implode( '::', $args );
        }
        
        if( !isset( self::$_instances[ $childClass ] ) ) {
            
            self::$_instances[ $childClass ]          = array();
            self::$_nbInstancesByClass[ $childClass ] = 0;
        }
        
        if( !isset( self::$_instances[ $childClass ][ $instanceName ] ) ) {
            
            $reflector = self::_newReflector( $reflectorClass, $args );
            
            self::$_instances[ $childClass ][ $instanceName ] = new $childClass( $reflector );
            self::$_nbInstancesByClass[ $childClass ]++;
            self::$_nbInstances++;
            
            self::$_instances[ $childClass ][ $instanceName ]->_instanceName   = $instanceName;
            self::$_instances[ $childClass ][ $instanceName ]->_reflectorClass = $reflectorClass;
        }
        
        return self::$_instances[ $childClass ][ $instanceName ];
    }
    
    /**
     * 
     */
    final private function _callMethod( $object, $name, array $args = array() )
    {
        // Checks if the method can be called
        if( !is_callable( array( $object, $name ) ) ) {
            
            // Called method does not exist
            throw new Woops_Core_Reflection_Base_Exception(
                'The method \'' . $name . '\' cannot be called on the current object',
                Woops_Core_Reflection_Base_Exception::EXCEPTION_BAD_METHOD
            );
        }
        
        $argsCount = count( $args );
        
        switch( $argsCount ) {
            
            case 0:
                
                return $object->$name();
                break;
            
            case 1:
                
                return $object->$name( $args[ 0 ] );
                break;
            
            case 2:
                
                return $object->$name( $args[ 0 ], $args[ 1 ] );
                break;
            
            case 3:
                
                return $object->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                break;
            
            case 4:
                
                return $object->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                break;
            
            case 5:
                
                return $object->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                break;
            
            default:
                
                return call_user_func_array( array( $object, $name ), $args );
                break;
        }
    }
    
    /**
     * 
     */
    final private function _getParameters()
    {
        if( !$this->_hasParameters ) {
            
            $parameters = $this->_reflector->getParameters();
            
            foreach( $parameters as $parameter ) {
                
                $this->_parameters[ $parameter->getName() ] = Woops_Core_Reflection_Parameter::getInstance(
                    $this->_reflector->getName(),
                    $parameter->getName()
                );
            }
            
            $this->_hasParameters = true;
        }
        
        return $this->_parameters;
    }
    
    /**
     * 
     */
    final private function _getExtension()
    {
        if( !$this->_hasExtension ) {
            
            $extension = $this->_reflector->getExtension();
            
            if( $extension ) {
                
                $this->_extension = Woops_Core_Reflection_Extension::getInstance(
                    $extension->getName()
                );
                
            } else {
                
                $this->_extension = $extension;
            }
            
            $this->_hasExtension = true;
        }
        
        return $this->_extension;
    }
    
    /**
     * 
     */
    final private function _getDeclaringFunction()
    {
        if( !$this->_hasDeclaringFunction ) {
            
            $declaringFunction        = $this->_reflector->getDeclaringFunction();
            
            if( get_class( $declaringFunction ) === 'ReflectionMethod' ) {
                
                $this->_declaringFunction = Woops_Core_Reflection_Class::getInstance(
                    $declaringFunction->getDeclaringClass()->getName(),
                    $declaringFunction->getName()
                );
                
            } else {
                
                $this->_declaringFunction = Woops_Core_Reflection_Function::getInstance(
                    $declaringFunction->getName()
                );
            }
            
            $this->_hasDeclaringFunction = true;
        }
        
        return $this->_declaringFunction;
    }
    
    /**
     * 
     */
    final private function _getDeclaringClass()
    {
        if( !$this->_hasDeclaringClass ) {
            
            $declaringClass        = $this->_reflector->getDeclaringClass();
            $this->_declaringClass = Woops_Core_Reflection_Class::getInstance(
                $declaringClass->getName()
            );
            
            $this->_hasDeclaringClass = true;
        }
        
        return $this->_declaringClass;
    }
    
    /**
     * 
     */
    final private function _getClass()
    {
        if( !$this->_hasClass ) {
            
            $class = $this->_reflector->getClass();
            
            if( $class ) {
                
                $this->_class = Woops_Core_Reflection_Class::getInstance(
                    $class->getName()
                );
                
            } else {
                
                $this->_class = $class;
            }
            
            $this->_hasClass = true;
        }
        
        return $this->_class;
    }
    
    /**
     * 
     */
    final private function _getFunctions()
    {
        if( !$this->_hasFunctions ) {
            
            $functions = $this->_reflector->getFunctions();
            
            foreach( $functions as $function ) {
                
                $this->_functions[ $function->getName() ] = Woops_Core_Reflection_Function::getInstance(
                    $function->getName()
                );
            }
            
            $this->_hasFunctions = true;
        }
        
        return $this->_functions;
    }
    
    /**
     * 
     */
    final private function _getClasses()
    {
        if( !$this->_hasClasses ) {
            
            $classes = $this->_reflector->getClasses();
            
            foreach( $classes as $class ) {
                
                $this->_classes[ $class->getName() ] = Woops_Core_Reflection_Class::getInstance(
                    $class->getName()
                );
            }
            
            $this->_hasClasses = true;
        }
        
        return $this->_classes;
    }
    
    /**
     * 
     */
    final private function _getMethod( $name )
    {
        if( !isset( $this->_methods[ $name ] ) ) {
            
            $method     = $this->_reflector->getMethod( $name );
            
            $reflection = Woops_Core_Reflection_Method::getInstance(
                $this->_reflector->getName(),
                $name
            );
            
            $this->_methods[ $name ] = $reflection;
                
            if( $reflection->isAbstract() ) {
                
                $this->_abstractMethods[ $name ] = $reflection;
            }
            
            if( $reflection->isFinal() ) {
                
                $this->_finalMethods[ $name ] = $reflection;
            }
            
            if( $reflection->isPrivate() ) {
                
                $this->_privateMethods[ $name ] = $reflection;
            }
            
            if( $reflection->isProtected() ) {
                
                $this->_protectedMethods[ $name ] = $reflection;
            }
            
            if( $reflection->isPublic() ) {
                
                $this->_publicMethods[ $name ] = $reflection;
            }
            
            if( $reflection->isStatic() ) {
                
                $this->_staticMethods[ $name ] = $reflection;
            }
        }
        
        return $this->_methods[ $name ];
    }
    
    /**
     * 
     */
    final private function _getMethods( $filter = 0 )
    {
        if( !$this->_hasMethods ) {
            
            $methods = $this->_reflector->getMethods();
            
            foreach( $methods as $method ) {
                
                $methodName = $method->getName();
                $className  = $this->_reflector->getName();
                
                if( !isset( $this->_methods[ $methodName ] ) ) {
                    
                    $reflection = Woops_Core_Reflection_Method::getInstance(
                        $className,
                        $methodName
                    );
                    
                    $this->_methods[ $methodName ] = $reflection;
                
                    if( $reflection->isAbstract() ) {
                        
                        $this->_abstractMethods[ $methodName ] = $reflection;
                    }
                    
                    if( $reflection->isFinal() ) {
                        
                        $this->_finalMethods[ $methodName ] = $reflection;
                    }
                    
                    if( $reflection->isPrivate() ) {
                        
                        $this->_privateMethods[ $methodName ] = $reflection;
                    }
                    
                    if( $reflection->isProtected() ) {
                        
                        $this->_protectedMethods[ $methodName ] = $reflection;
                    }
                    
                    if( $reflection->isPublic() ) {
                        
                        $this->_publicMethods[ $methodName ] = $reflection;
                    }
                    
                    if( $reflection->isStatic() ) {
                        
                        $this->_staticMethods[ $methodName ] = $reflection;
                    }
                }
            }
            
            $this->_hasMethods = true;
        }
        
        if( !$filter ) {
            
            return $this->_methods;
        }
        
        $methods = array();
        
        if( $filter & ReflectionMethod::IS_ABSTRACT ) {
            
            $methods = array_merge( $this->_abstractMethods, $methods );
        }
        
        if( $filter & ReflectionMethod::IS_FINAL ) {
            
            $methods = array_merge( $this->_finalMethods, $methods );
        }
        
        if( $filter & ReflectionMethod::IS_PRIVATE ) {
            
            $methods = array_merge( $this->_privateMethods, $methods );
        }
        
        if( $filter & ReflectionMethod::IS_PROTECTED ) {
            
            $methods = array_merge( $this->_protectedMethods, $methods );
        }
        
        if( $filter & ReflectionMethod::IS_PUBLIC ) {
            
            $methods = array_merge( $this->_publicMethods, $methods );
        }
        
        if( $filter & ReflectionMethod::IS_STATIC ) {
            
            $methods = array_merge( $this->_staticMethods, $methods );
        }
        
        ksort( $methods );
        
        return $methods;
    }
    
    /**
     * 
     */
    final private function _getConstructor()
    {
        if( !$this->_hasConstructor ) {
            
            $constructor = $this->_reflector->getConstructor();
            
            if( $constructor ) {
                
                $this->_constructor = Woops_Core_Reflection_Method::getInstance(
                    $constructor->getDeclaringClass()->getName(),
                    $constructor->getName()
                );
                
            } else {
                
                $this->_constructor = $constructor;
            }
            
            $this->_hasConstructor = true;
        }
        
        return $this->_constructor;
    }
    
    /**
     * 
     */
    final private function _getInterfaces()
    {
        if( !$this->_hasInterfaces ) {
            
            $interfaces = $this->_reflector->getInterfaces();
            
            foreach( $interfaces as $interface ) {
                
                $this->_interfaces[ $interface->getName() ] = Woops_Core_Reflection_Class::getInstance(
                    $interface->getName()
                );
            }
            
            $this->_hasInterfaces = true;
        }
        
        return $this->_interfaces;
    }
    
    /**
     * 
     */
    final private function _getProperty( $name )
    {
        if( !isset( $this->_properties[ $name ] ) ) {
            
            $property   = $this->_reflector->getProperty( $name );
            
            $reflection = Woops_Core_Reflection_Property::getInstance(
                $this->_reflector->getName(),
                $name
            );
            
            $this->_properties[ $name ] = $reflection;
            
            if( $reflection->isPrivate() ) {
                
                $this->_privateProperties[ $name ] = $reflection;
            }
            
            if( $reflection->isProtected() ) {
                
                $this->_protectedProperties[ $name ] = $reflection;
            }
            
            if( $reflection->isPublic() ) {
                
                $this->_publicProperties[ $name ] = $reflection;
            }
            
            if( $reflection->isStatic() ) {
                
                $this->_staticProperties[ $name ] = $reflection;
            }
        }
        
        return $this->_properties[ $name ];
    }
    
    /**
     * 
     */
    final private function _getProperties( $filter = 0 )
    {
        if( !$this->_hasProperties ) {
            
            $properties = $this->_reflector->getProperties();
            
            foreach( $properties as $property ) {
                
                $propertyName = $property->getName();
                $className    = $this->_reflector->getName();
                
                if( !isset( $this->_methods[ $propertyName ] ) ) {
                    
                    $reflection = Woops_Core_Reflection_Property::getInstance(
                        $className,
                        $propertyName
                    );
                    
                    $this->_properties[ $propertyName ] = $reflection;
                    
                    if( $reflection->isPrivate() ) {
                        
                        $this->_privateProperties[ $propertyName ] = $reflection;
                    }
                    
                    if( $reflection->isProtected() ) {
                        
                        $this->_protectedProperties[ $propertyName ] = $reflection;
                    }
                    
                    if( $reflection->isPublic() ) {
                        
                        $this->_publicProperties[ $propertyName ] = $reflection;
                    }
                    
                    if( $reflection->isStatic() ) {
                        
                        $this->_staticProperties[ $propertyName ] = $reflection;
                    }
                }
            }
            
            $this->_hasProperties = true;
        }
        
        if( !$filter ) {
            
            return $this->_properties;
        }
        
        $properties = array();
        
        if( $filter & ReflectionProperty::IS_PRIVATE ) {
            
            $properties = array_merge( $this->_privateProperties, $properties );
        }
        
        if( $filter & ReflectionProperty::IS_PROTECTED ) {
            
            $properties = array_merge( $this->_protectedProperties, $properties );
        }
        
        if( $filter & ReflectionProperty::IS_PUBLIC ) {
            
            $properties = array_merge( $this->_publicProperties, $properties );
        }
        
        if( $filter & ReflectionProperty::IS_STATIC ) {
            
            $properties = array_merge( $this->_staticProperties, $properties );
        }
        
        ksort( $properties );
        
        return $properties;
    }
    
    /**
     * 
     */
    final private function _getParentClass()
    {
        if( !$this->_hasParentClass ) {
            
            $parentClass = $this->_reflector->getParentClass();
            
            if( $parentClass ) {
                
                $this->_parentClass = Woops_Core_Reflection_Class::getInstance(
                    $parentClass->getName()
                );
                
            } else {
                
                $this->_parentClass = $parentClass;
            }
            
            $this->_hasParentClass = true;
        }
        
        return $this->_parentClass;
    }
    
    /**
     * 
     */
    final private function _isSubclassOf( $class )
    {
        if( is_object( $class ) && $class instanceof self ) {
            
            return $this->_reflector->isSubclassOf( $class->_reflector );
            
        } else {
            
            return $this->_reflector->isSubclassOf( $class );
        }
    }
}

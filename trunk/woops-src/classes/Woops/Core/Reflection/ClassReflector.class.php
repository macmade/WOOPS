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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Core\Reflection;

/**
 * Class reflector
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Reflection
 */
class ClassReflector extends \Woops\Core\Reflection
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    const IS_EXPLICIT_ABSTRACT = \ReflectionClass::IS_EXPLICIT_ABSTRACT;
    const IS_FINAL             = \ReflectionClass::IS_FINAL;
    const IS_IMPLICIT_ABSTRACT = \ReflectionClass::IS_IMPLICIT_ABSTRACT;
    
    /**
     * 
     */
    private $_hasExtension              = false;
    
    /**
     * 
     */
    private $_hasMethods                = false;
    
    /**
     * 
     */
    private $_hasConstructor            = false;
    
    /**
     * 
     */
    private $_hasInterfaces             = false;
    
    /**
     * 
     */
    private $_hasProperties             = false;
    
    /**
     * 
     */
    private $_hasParentClass            = false;
    
    /**
     * 
     */
    private $_extension                 = NULL;
    
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
    private $_constructor               = NULL;
    
    /**
     * 
     */
    private $_interfaces                = array();
    
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
    private $_parentClass               = NULL;
    
    /**
     * 
     */
    public function isSingleton()
    {
        return $this->_reflector->implementsInterface( '\Woops\Core\Singleton\ObjectInterface' );
    }
    
    /**
     * 
     */
    public function isMultiSingleton()
    {
        return $this->_reflector->implementsInterface( '\Woops\Core\MultiSingleton\ObjectInterface' );
    }
    
    /**
     * 
     */
    public function isAopReady()
    {
        return $this->_reflector->isSubclassOf( '\Woops\Core\Aop\Advisor' );
    }
    
    /**
     * 
     */
    public function getMethod( $name )
    {
        if( !isset( $this->_methods[ $name ] ) ) {
            
            $method     = $this->_reflector->getMethod( $name );
            
            $reflection = MethodReflector::getInstance(
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
    public function getMethods( $filter = 0 )
    {
        if( !$this->_hasMethods ) {
            
            $methods = $this->_reflector->getMethods();
            
            foreach( $methods as $method ) {
                
                $methodName = $method->getName();
                $className  = $this->_reflector->getName();
                
                if( !isset( $this->_methods[ $methodName ] ) ) {
                    
                    $reflection = MethodReflector::getInstance(
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
        
        if( $filter & \ReflectionMethod::IS_ABSTRACT ) {
            
            $methods = array_merge( $this->_abstractMethods, $methods );
        }
        
        if( $filter & \ReflectionMethod::IS_FINAL ) {
            
            $methods = array_merge( $this->_finalMethods, $methods );
        }
        
        if( $filter & \ReflectionMethod::IS_PRIVATE ) {
            
            $methods = array_merge( $this->_privateMethods, $methods );
        }
        
        if( $filter & \ReflectionMethod::IS_PROTECTED ) {
            
            $methods = array_merge( $this->_protectedMethods, $methods );
        }
        
        if( $filter & \ReflectionMethod::IS_PUBLIC ) {
            
            $methods = array_merge( $this->_publicMethods, $methods );
        }
        
        if( $filter & \ReflectionMethod::IS_STATIC ) {
            
            $methods = array_merge( $this->_staticMethods, $methods );
        }
        
        ksort( $methods );
        
        return $methods;
    }
    
    /**
     * 
     */
    public function getConstructor()
    {
        if( !$this->_hasConstructor ) {
            
            $constructor = $this->_reflector->getConstructor();
            
            if( $constructor ) {
                
                $this->_constructor = MethodReflector::getInstance(
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
    public function getInterfaces()
    {
        if( !$this->_hasInterfaces ) {
            
            $interfaces = $this->_reflector->getInterfaces();
            
            foreach( $interfaces as $interface ) {
                
                $this->_interfaces[ $interface->getName() ] = ClassReflector::getInstance(
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
    public function getProperty( $name )
    {
        if( !isset( $this->_properties[ $name ] ) ) {
            
            $property   = $this->_reflector->getProperty( $name );
            
            $reflection = PropertyReflector::getInstance(
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
    public function getProperties( $filter = 0 )
    {
        if( !$this->_hasProperties ) {
            
            $properties = $this->_reflector->getProperties();
            
            foreach( $properties as $property ) {
                
                $propertyName = $property->getName();
                $className    = $this->_reflector->getName();
                
                if( !isset( $this->_methods[ $propertyName ] ) ) {
                    
                    $reflection = PropertyReflector::getInstance(
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
        
        if( $filter & \ReflectionProperty::IS_PRIVATE ) {
            
            $properties = array_merge( $this->_privateProperties, $properties );
        }
        
        if( $filter & \ReflectionProperty::IS_PROTECTED ) {
            
            $properties = array_merge( $this->_protectedProperties, $properties );
        }
        
        if( $filter & \ReflectionProperty::IS_PUBLIC ) {
            
            $properties = array_merge( $this->_publicProperties, $properties );
        }
        
        if( $filter & \ReflectionProperty::IS_STATIC ) {
            
            $properties = array_merge( $this->_staticProperties, $properties );
        }
        
        ksort( $properties );
        
        return $properties;
    }
    
    /**
     * 
     */
    public function getParentClass()
    {
        if( !$this->_hasParentClass ) {
            
            $parentClass = $this->_reflector->getParentClass();
            
            if( $parentClass ) {
                
                $this->_parentClass = ClassReflector::getInstance(
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
    public function isSubclassOf( $class )
    {
        if( is_object( $class ) && $class instanceof self ) {
            
            return $this->_reflector->isSubclassOf( $class->_reflector );
            
        } else {
            
            return $this->_reflector->isSubclassOf( $class );
        }
    }
    
    /**
     * 
     */
    public function getExtension()
    {
        if( !$this->_hasExtension ) {
            
            $extension = $this->_reflector->getExtension();
            
            if( $extension ) {
                
                $this->_extension = ExtensionReflector::getInstance(
                    $extension->getName()
                );
                
            } else {
                
                $this->_extension = $extension;
            }
            
            $this->_hasExtension = true;
        }
        
        return $this->_extension;
    }
}

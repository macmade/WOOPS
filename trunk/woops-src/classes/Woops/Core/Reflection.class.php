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

# $Id: Interface.class.php 434 2009-02-24 15:19:13Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Core;

/**
 * Base class for the reflection classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core
 */
abstract class Reflection extends \Woops\Core\MultiSIngleton\Base
{
    /**
     * 
     */
    protected $_reflector = NULL;
    
    /**
     * 
     */
    final public function __call( $name, array $args )
    {
        if( is_object( $this->_reflector ) ) {
            
            switch( count( $args ) ) {
                
                case 0:
                    
                    return $this->_reflector->$name();
                    break;
                
                case 1:
                    
                    return $this->_reflector->$name( $args[ 0 ] );
                    break;
                
                case 2:
                    
                    return $this->_reflector->$name( $args[ 0 ], $args[ 1 ] );
                    break;
                
                case 3:
                    
                    return $this->_reflector->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                    break;
                
                case 4:
                    
                    return $this->_reflector->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                    break;
                
                case 5:
                    
                    return $this->_reflector->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                    break;
                
                default:
                    
                    return call_user_func_array( array( $this->_reflector, $name ), $args );
                    break;
            }
            
        } else {
            
            throw new Reflection\Exception(
                '',
                Reflection\Exception::EXCEPTION_
            );
        }
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
    private static function _createReflectorObject( $class, array $args )
    {
        switch( $class ) {
            
            case 'Woops\Core\Reflection\ClassReflector':
                
                $reflectorClass = 'ReflectionClass';
                break;
            
            case 'Woops\Core\Reflection\ExtensionReflector':
                
                $reflectorClass = 'ReflectionExtension';
                break;
            
            case 'Woops\Core\Reflection\FunctionReflector':
                
                $reflectorClass = 'ReflectionFunction';
                break;
            
            case 'Woops\Core\Reflection\MethodReflector':
                
                $reflectorClass = 'ReflectionMethod';
                break;
            
            case 'Woops\Core\Reflection\ObjectReflector':
                
                $reflectorClass = 'ReflectionObject';
                break;
            
            case 'Woops\Core\Reflection\ParameterReflector':
                
                $reflectorClass = 'ReflectionParameter';
                break;
            
            case 'Woops\Core\Reflection\PropertyReflector':
                
                $reflectorClass = 'ReflectionProperty';
                break;
        }
        
        if( isset( $reflectorClass ) ) {
            
            switch( count( $args ) ) {
                
                case 1:
                    
                    $reflector = new $reflectorClass( $args[ 0 ] );
                    break;
                
                case 2:
                    
                    $reflector = new $reflectorClass( $args[ 0 ], $args[ 1 ] );
                    break;
                
                default:
                    
                    throw new Reflection\Exception(
                        '',
                        Reflection\Exception::EXCEPTION_
                    );
                    break;
            }
            
            return $reflector;
            
        } else {
            
            return false;
        }
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @param   string                                       The instance name
     * @return  Woops\Core\MultiSingleton\ObjectInterface    The unique instance of the class
     * @see     __construct
     */
    public static function getInstance( $name )
    {
        $args  = func_get_args();
        $class = get_called_class();
        
        if( isset( $args[ 0 ] ) && is_object( $args[ 0 ] ) ) {
            
            $hash         = spl_object_hash( $args[ 0 ] );
            $instanceName = ( isset( $args[ 1 ] ) ) ? $hash . '::' . $args[ 1 ] : $hash;
            
        } else {
            
            $instanceName = implode( '::', $args );
        }
        
        $instance = parent::getInstance( $instanceName );
        
        if( !isset( $instance->_reflector ) ) {
            
            $instance->_reflector = self::_createReflectorObject( $class, $args );
        }
        
        return $instance;
    }
    
    /**
     * 
     */
    public static function getClassReflector( $name )
    {
        return Reflection\ClassReflector::getInstance( $name );
    }
    
    /**
     * 
     */
    public static function getExtensionReflector( $name )
    {
        return Reflection\ExtensionReflector::getInstance( $name );
    }
    
    /**
     * 
     */
    public static function getFunctionReflector( $name )
    {
        return Reflection\FunctionReflector::getInstance( $name );
    }
    
    /**
     * 
     */
    public static function getMethodReflector( $class, $name )
    {
        return Reflection\MethodReflector::getInstance( $class, $name );
    }
    
    /**
     * 
     */
    public static function getObjectReflector( $object )
    {
        return Reflection\ObjectReflector::getInstance( $object );
    }
    
    /**
     * 
     */
    public static function getParameterReflector( $function, $parameter )
    {
        return Reflection\ParameterReflector::getInstance( $function, $parameter );
    }
    
    /**
     * 
     */
    public static function getPropertyReflector( $class, $name )
    {
        return Reflection\PropertyReflector::getInstance( $class, $name );
    }
    
    /**
     * 
     */
    public static function getNameSpaceReflector( $name )
    {
        return Reflection\NameSpaceReflector::getInstance( $name );
    }
}

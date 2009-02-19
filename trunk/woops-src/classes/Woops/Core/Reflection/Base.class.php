<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
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
abstract class Woops_Core_Reflection_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    protected static $_instances          = array();
    
    /**
     * 
     */
    protected static $_nbInstancesByClass = array();
    
    /**
     * 
     */
    protected static $_nbInstances        = 0;
    
    /**
     * 
     */
    protected $_reflector                 = NULL;
    
    /**
     * 
     */
    protected $_instanceName              = '';
    
    /**
     * 
     */
    final private function __construct( Reflector $reflector )
    {
        $this->_reflector = $reflector;
    }
    
    /**
     * 
     */
    final private function __clone()
    {}
    
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
        $argsCount = count( $args );
        
        switch( $argsCount ) {
            
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
        }
        
        return self::$_instances[ $childClass ][ $instanceName ];
    }
}

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
namespace Woops\Unit;

/**
 * Abstract class for the unit classes
 * 
 * The idea of such a set of classes came from the Zend Framework, but this one
 * works a bit differently.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
abstract class Base extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    protected $_types       = array();
    
    /**
     * 
     */
    protected $_value       = 0;
    
    /**
     * 
     */
    protected $_type        = '';
    
    /**
     * 
     */
    protected $_defaultType = '';
    
    /**
     * 
     */
    public function __construct( $value, $type = false )
    {
        $this->setValue( $value, $type );
    }
    
    /**
     * 
     */
    public function __toString()
    {
        return $this->_value;
    }
    
    /**
     * 
     */
    public function __call( $name, array $args = array() )
    {
        if( substr( $name, 0, 2 ) === 'as' ) {
            
            $type = strtoupper( preg_replace( '/(.)([A-Z])/', '\1_\2', substr( $name, 2 ) ) );
            
        } else {
            
            $type = $this->_defaultType;
        }
        
        return ( isset( $args[ 0 ] ) ) ? $this->getValue( $type, $args[ 0 ] ) : $this->getValue( $type );
    }
    
    /**
     * 
     */
    protected function _checkClass( Base $unit )
    {
        $unitClass = get_class( $unit );
        $thisClass = get_class( $this );
        
        if( $unitClass !== $thisClass ) {
            
            throw new Base\Exception(
                'Cannot compare a \'' . $unitClass . '\' unit with a \'' . $thisClass . '\' unit',
                Base\Exception::EXCEPTION_INVALID_UNIT
            );
        }
    }
    
    /**
     * 
     */
    public function _convertTo( $value, $type )
    {
        foreach( $this->_types[ $type ] as $operation ) {
            
            if( !is_array( $operation ) ) {
                
                continue;
            }
            
            switch( $operation[ 0 ] ) {
                
                case '+':
                    
                    $value += $operation[ 1 ];
                    break;
                    
                case '-':
                    
                    $value -= $operation[ 1 ];
                    break;
                    
                case '*':
                    
                    $value *= $operation[ 1 ];
                    break;
                    
                case '/':
                    
                    $value /= $operation[ 1 ];
                    break;
                    
                default:
                    
                    break;
            }
        }
        
        return $value;
    }
    
    /**
     * 
     */
    public function _convertFrom( $value, $type )
    {
        $operations = array_reverse( $this->_types[ $type ] );
        
        foreach( $operations as $operation ) {
            
            if( !is_array( $operation ) ) {
                
                continue;
            }
            
            switch( $operation[ 0 ] ) {
                
                case '+':
                    
                    $value -= $operation[ 1 ];
                    break;
                    
                case '-':
                    
                    $value += $operation[ 1 ];
                    break;
                    
                case '*':
                    
                    $value /= $operation[ 1 ];
                    break;
                    
                case '/':
                    
                    $value *= $operation[ 1 ];
                    break;
                    
                default:
                    
                    break;
            }
        }
        
        return $value;
    }
    
    /**
     * 
     */
    public function setValue( $value, $type = false )
    {
        if( !$type ) {
            
            $this->_value = $value;
            
        } else {
        
            if( !isset( $this->_types[ $type ] ) ) {
                
                throw new Base\Exception(
                    'Invalid unit type (' . $type . ')',
                    Base\Exception::EXCEPTION_INVALID_TYPE
                );
            }
            
            $this->_value = $this->_convertFrom( $value, $type );
        }
    }
    
    /**
     * 
     */
    public function getValue( $type = false, $round = 2 )
    {
        if( !$type ) {
            
            return $this->_value;
        }
        
        if( !isset( $this->_types[ $type ] ) ) {
            
            throw new Base\Exception(
                'Invalid unit type (' . $type . ')',
                Base\Exception::EXCEPTION_INVALID_TYPE
            );
        }
        
        return $this->_convertTo( $this->_value, $type );
    }
    
    /**
     * 
     */
    public function isBigger( Base $unit )
    {
        $this->_checkClass( $unit );
        
        return $this->_value > $unit->getValue();
    }
    
    /**
     * 
     */
    public function isSmaller( Base $unit )
    {
        $this->_checkClass( $unit );
        
        return $this->_value < $unit->getValue();
    }
    
    /**
     * 
     */
    public function isEqual( Base $unit )
    {
        $this->_checkClass( $unit );
        
        return $this->_value == $unit->getValue();
    }
    
    /**
     * 
     */
    public function add( Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value += $unit->getValue();
    }
    
    /**
     * 
     */
    public function substract( Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value -= $unit->getValue();
    }
    
    /**
     * 
     */
    public function multiply( Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value *= $unit->getValue();
    }
    
    /**
     * 
     */
    public function divide( Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value /= $unit->getValue();
    }
}

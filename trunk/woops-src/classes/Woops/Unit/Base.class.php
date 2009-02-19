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
 * Abstract class for the unit classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Unit
 */
abstract class Woops_Unit_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
    protected function _checkClass( Woops_Unit_Base $unit )
    {
        $unitClass = get_class( $unit );
        $thisClass = get_class( $this );
        
        if( $unitClass !== $thisClass ) {
            
            throw new Woops_Unit_Base_Exception(
                'Cannot compare a \'' . $unitClass . '\' unit with a \'' . $thisClass . '\' unit',
                Woops_Unit_Base_Exception::EXCEPTION_INVALID_UNIT
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
        foreach( $this->_types[ $type ] as $operation ) {
            
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
                
                throw new Woops_Unit_Base_Exception(
                    'Invalid unit type (' . $type . ')',
                    Woops_Unit_Base_Exception::EXCEPTION_INVALID_TYPE
                );
            }
            
            $this->_value = $this->_convertFrom( $value, $type );
        }
    }
    
    /**
     * 
     */
    public function getValue( $type = false )
    {
        if( !$type ) {
            
            return $this->_value;
        }
        
        if( !isset( $this->_types[ $type ] ) ) {
            
            throw new Woops_Unit_Base_Exception(
                'Invalid unit type (' . $type . ')',
                Woops_Unit_Base_Exception::EXCEPTION_INVALID_TYPE
            );
        }
        
        return $this->_convertTo( $this->_value, $type );
    }
    
    /**
     * 
     */
    public function isBigger( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        return $this->_value > $unit->getValue();
    }
    
    /**
     * 
     */
    public function isSmaller( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        return $this->_value < $unit->getValue();
    }
    
    /**
     * 
     */
    public function isEqual( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        return $this->_value == $unit->getValue();
    }
    
    /**
     * 
     */
    public function add( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value += $unit->getValue();
    }
    
    /**
     * 
     */
    public function substract( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value -= $unit->getValue();
    }
    
    /**
     * 
     */
    public function multiply( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value *= $unit->getValue();
    }
    
    /**
     * 
     */
    public function divide( Woops_Unit_Base $unit )
    {
        $this->_checkClass( $unit );
        
        $this->_value /= $unit->getValue();
    }
}

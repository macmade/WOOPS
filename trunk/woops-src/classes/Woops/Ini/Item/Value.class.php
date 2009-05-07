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
 * INI value class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Ini.Item
 */
class Woops_Ini_Item_Value extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The name of the value item
     */
    protected $_name  = '';
    
    /**
     * The value of the value item
     */
    protected $_value = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The name of the value item
     * @param   string  An optionnal value for the value item
     * @return  void
     */
    public function __construct( $name, $value = '' )
    {
        // Stores the name
        $this->_name = ( string )$name;
        
        // Checks the value
        if( $value === true ) {
            
            // Boolean
            $this->_value = 'On';
            
        } elseif( $value === false ) {
            
            // Boolean
            $this->_value = 'Off';
            
        } else {
            
            // Stores the value
            $this->_value = ( string )$value;
        }
    }
    
    /**
     * Returns the INI value as a string
     * 
     * @return  The INI value, in the INI format
     */
    public function __toString()
    {
        // Returns the value
        return $this->_name . ' = ' . $this->_value;
    }
    
    /**
     * Gets the name of the value item
     * 
     * @return  string  The name of the value item
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Gets the value of the value item
     * 
     * @return  string  The value of the value item
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Sets the value of the value item
     * 
     * @param   string  The value of the value item
     * @return  void
     */
    public function setValue( $value )
    {
        // Checks the value
        if( $value === true ) {
            
            // Boolean
            $this->_value = 'On';
            
        } elseif( $value === false ) {
            
            // Boolean
            $this->_value = 'Off';
            
        } else {
            
            // Stores the value
            $this->_value = ( string )$value;
        }
    }
}

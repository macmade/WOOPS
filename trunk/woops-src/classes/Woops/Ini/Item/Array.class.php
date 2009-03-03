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
 * INI array class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Ini.Item
 */
class Woops_Ini_Item_Array
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic = false;
    
    /**
     * The string utilities
     */
    protected static $_str     = NULL;
    
    /**
     * The name of the array item
     */
    protected $_name           = '';
    
    /**
     * The values of the array item
     */
    protected $_values         = array();
    
    /**
     * Class constructor
     * 
     * @param   string  The name of the array item
     * @param   string  An optionnal array with values
     * @return  void
     */
    public function __construct( $name, array $values = array() )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Stores the real name
        $this->_name = ( string )$name;
        
        // Process each passed values
        foreach( $values as $value ) {
            
            // Adds the value item
            $this->addValue( $value );
        }
    }
    
    /**
     * Returns the INI array as a string
     * 
     * @return  The INI array, in the INI format
     */
    public function __toString()
    {
        // Checks for values
        if( !count( $this->_values ) ) {
            
            // Noting to return
            return '';
        }
        
        // Storage
        $array = '';
        
        // Process ech value
        foreach( $this->_values as $key => $value ) {
            
            // Adds the current value
            $array .= ( string )$value . self::$_str->NL;
        }
        
        // Returns the INI array
        return substr( $array, 0, -1 );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = Woops_String_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Adds a value to the array item
     * 
     * @param   string  The value to add
     */
    public function addValue( $value )
    {
        // Creates the value object
        $object          = new Woops_Ini_Item_Value( $this->_name . '[]', $value );
        
        // Stores the value object
        $this->_values[] = $object;
        
        // Returns the value object
        return $object;
    }
    
    /**
     * Gets the name of the array item
     * 
     * @return  string  The name of the array item
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Gets the values of the array item
     * 
     * @return  array   An array with instances of the Woops_Ini_Item_Value class
     */
    public function getValues()
    {
        return $this->_values;
    }
}

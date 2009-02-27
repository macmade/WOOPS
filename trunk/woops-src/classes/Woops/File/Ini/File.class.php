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
 * INI file class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Ini
 */
class Woops_File_Ini_File implements Iterator, ArrayAccess
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Wether the static variables are set or not
     */
    private static $_hasStatic = false;
    
    /**
     * The string utilities
     */
    protected static $_str     = NULL;
    
    /**
     * The INI file items
     */
    protected $_items          = array();
    
    /**
     * The position of the SPL iterator
     */
    protected $_iteratorPos    = 0;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
    }
    
    /**
     * Returns the INI file as a string
     * 
     * @return  The INI file, in the INI format
     */
    public function __toString()
    {
        // Storage
        $file = '';
        
        // Process each items
        foreach( $this->_items as $name => $object ) {
            
            // Writes the object
            $file .= ( string )$object . self::$_str->NL;
        }
        
        // Ensures the items pointer is untouched
        reset( $this->_items );
        
        // Returns the INI file
        return $file;
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
     * Gets the current item (Iterator method)
     * 
     * @return  object  The item object (Woops_File_Ini_Item_Section, Woops_File_Ini_Item_Value or Woops_File_Ini_Item_Array) if it exists, otherwise NULL
     */
    public function current()
    {
        return current( $this->_items );
    }
    
    /**
     * Gets the name of the current item (Iterator method)
     * 
     * @return  string  the name of the current item
     */
    public function key()
    {
        return key( $this->_items );
    }
    
    /**
     * Advances the iterator pointer (Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        next( $this->_items );
        $this->_iteratorPos++;
    }
    
    /**
     * Resets the iterator pointer (Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        reset( $this->_items );
        $this->_iteratorPos = 0;
    }
    
    /**
     * Checks if more INI items can be iterated (Iterator method)
     * 
     * @return  boolean
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_items );
    }
    
    /**
     * Gets an item (ArrayAccess method)
     * 
     * @param   string  The name of the item to get
     * @return  object  The item object (Woops_File_Ini_Item_Section, Woops_File_Ini_Item_Value or Woops_File_Ini_Item_Array) if it exists, otherwise NULL
     * @see     getItem
     */
    public function offsetGet( $name )
    {
        return $this->getItem( $name );
    }
    
    /**
     * Creates a value item in the INI file (ArrayAccess method)
     * 
     * @param   string                      The name of the value item
     * @param   string                      The value for the value item
     * @return  Woops_File_Ini_Item_Value   The new value object
     * @see     newValueItem
     */
    public function offsetSet( $name, $value )
    {
        return $this->newValueItem( $name, $value );
    }
    
    /**
     * Checks if an item exists in the INI file (ArrayAccess method)
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item exists, otherwise false
     * @see     itemExists
     */
    public function offsetExists( $name )
    {
        return $this->itemExists( $name );
    }
    
    /**
     * Removes an item form the INI file (ArrayAccess method)
     * 
     * @param   string  The name of the item to remove
     * @return  void
     * @see     removeItem
     */
    public function offsetUnset( $name )
    {
        $this->removeItem( $name );
    }
    
    /**
     * Creates a section item in the INI file
     * 
     * @param   string                      The name of the section item
     * @return  Woops_File_Ini_Item_Section The new section object
     */
    public function newSectionItem( $name )
    {
        // Ensures the name is a string
        $name                  = ( string )$name;
        
        // Creates and stores the section object
        $this->_items[ $name ] = new Woops_File_Ini_Item_Section( $name );
        
        // Returns the section object
        return $this->_items[ $name ];
    }
    
    /**
     * Creates a value item in the INI file
     * 
     * @param   string                      The name of the value item
     * @param   string                      An optionnal value for the value item
     * @return  Woops_File_Ini_Item_Value   The new value object
     */
    public function newValueItem( $name, $value = '' )
    {
        // Ensures the name is a string
        $name                  = ( string )$name;
        
        // Creates and stores the value object
        $this->_items[ $name ] = new Woops_File_Ini_Item_Value( $name, $value );
        
        // Returns the value object
        return $this->_items[ $name ];
    }
    
    /**
     * Creates an array item in the INI file
     * 
     * @param   string                      The name of the array item
     * @param   string                      An optionnal array with values for the array item
     * @return  Woops_File_Ini_Item_Array   The new array object
     */
    public function newArrayItem( $name, array $values = array() )
    {
        // Ensures the name is a string
        $name                  = ( string )$name;
        
        // Creates and stores the array object
        $this->_items[ $name ] = new Woops_File_Ini_Item_Array( $name, $values );
        
        // Returns the array object
        return $this->_items[ $name ];
    }
    
    /**
     * Adds a section item in the INI file
     * 
     * @param   Woops_File_Ini_Item_Section The section object to add
     * @return  void
     */
    public function addSectionItem( Woops_File_Ini_Item_Section $section )
    {
        $this->_items[ $section->getName() ] = $section;
    }
    
    /**
     * Adds a value item in the INI file
     * 
     * @param   Woops_File_Ini_Item_Value   The value object to add
     * @return  void
     */
    public function addValueItem( Woops_File_Ini_Item_Value $value )
    {
        $this->_items[ $value->getName() ] = $value;
    }
    
    /**
     * Adds an array item in the INI file
     * 
     * @param   Woops_File_Ini_Item_Array   The array object to add
     * @return  void
     */
    public function addArrayItem( Woops_File_Ini_Item_Array $array )
    {
        $this->_items[ $array->getName() ] = $array;
    }
    
    /**
     * Checks if an item exists in the INI file
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item exists, otherwise false
     */
    public function itemExists( $name )
    {
        return ( isset( $this->_items[ ( string )$name ] ) ) ? true : false;
    }
    
    /**
     * Checks if an item in the INI file is an INI section
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item is a section, otherwise false
     */
    public function isSection()
    {
        return ( $this->itemExists( $name ) && $this->_items[ ( string )$name ] instanceof Woops_File_Ini_Item_Section ) ? true : false;
    }
    
    /**
     * Checks if an item in the INI file is an INI value
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item is a value, otherwise false
     */
    public function isValue()
    {
        return ( $this->itemExists( $name ) && $this->_items[ ( string )$name ] instanceof Woops_File_Ini_Item_Value ) ? true : false;
    }
    
    /**
     * Checks if an item in the INI file is an INI array
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item is an array, otherwise false
     */
    public function isArray()
    {
        return ( $this->itemExists( $name ) && $this->_items[ ( string )$name ] instanceof Woops_File_Ini_Item_Array ) ? true : false;
    }
    
    /**
     * Gets an item form the INI file
     * 
     * @param   string  The name of the item to get
     * @return  object  The item object (Woops_File_Ini_Item_Section, Woops_File_Ini_Item_Value or Woops_File_Ini_Item_Array) if it exists, otherwise NULL
     */
    public function getItem( $name )
    {
        return ( isset( $this->_items[ ( string )$name ] ) ) ? $this->_items[ ( string )$name ] : NULL;
    }
    
    /**
     * Removes an item form the INI file
     * 
     * @param   string  The name of the item to remove
     * @return  void
     */
    public function removeItem( $name )
    {
        unset( $this->_items[ ( string )$name ] );
    }
}

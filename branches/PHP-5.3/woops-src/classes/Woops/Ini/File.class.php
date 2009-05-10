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
namespace Woops\Ini;

/**
 * INI file class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Ini
 */
class File extends \Woops\Core\Object implements \Iterator, \ArrayAccess
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Whether the static variables are set or not
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
        $file    = '';
        $counter = 0;
        
        // Process each items
        foreach( $this->_items as $name => $object ) {
            
            // Checks if the current object is a section
            if( $object instanceof Item\Section && $counter > 0 ) {
                
                // Adds a blank line
                $file .= self::$_str->NL;
            }
            
            // Writes the object
            $file .= ( string )$object . self::$_str->NL;
            
            // Increases the counter
            $counter++;
        }
        
        // Ensures the items pointer is untouched
        reset( $this->_items );
        
        // Returns the INI file
        return $file;
    }
    
    /**
     * Gets an item
     * 
     * @param   string  The name of the item to get
     * @return  object  The item object (Woops\Ini\Item\Section, Woops\Ini\Item\Value or Woops\Ini\Item\Array) if it exists, otherwise NULL
     * @see     getItem
     */
    public function __get( $name )
    {
        return $this->getItem( $name );
    }
    
    /**
     * Creates a value item in the INI file
     * 
     * @param   string                  The name of the value item
     * @param   string                  The value for the value item
     * @return  Woops\Ini\Item\Value    The new value object
     * @see     newValueItem
     */
    public function __set( $name, $value )
    {
        return $this->newValueItem( $name, $value );
    }
    
    /**
     * Checks if an item exists in the INI file
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item exists, otherwise false
     * @see     itemExists
     */
    public function __isset( $name )
    {
        return $this->itemExists( $name );
    }
    
    /**
     * Removes an item form the INI file
     * 
     * @param   string  The name of the item to remove
     * @return  void
     * @see     removeItem
     */
    public function __unset( $name )
    {
        $this->removeItem( $name );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = \Woops\String\Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Gets the current item (Iterator method)
     * 
     * @return  object  The item object (Woops\Ini\Item\Section, Woops\Ini\Item\Value or Woops\Ini\Item\Array) if it exists, otherwise NULL
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
     * @return  object  The item object (Woops\Ini\Item\Section, Woops\Ini\Item\Value or Woops\Ini\Item\Array) if it exists, otherwise NULL
     * @see     getItem
     */
    public function offsetGet( $name )
    {
        return $this->getItem( $name );
    }
    
    /**
     * Creates a value item in the INI file (ArrayAccess method)
     * 
     * @param   string                  The name of the value item
     * @param   string                  The value for the value item
     * @return  Woops\Ini\Item\Value    The new value object
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
     * Returns the INI file as an array
     * 
     * @return  array   An array with the INI file items
     */
    public function toArray()
    {
        // Storage
        $ini = array();
        
        // Process each items
        foreach( $this->_items as $name => $object ) {
            
            // Gets the item name
            $name = $object->getName();
            
            // Checks the kind of item
            if( $object instanceof Item\Value ) {
                
                // Normal value
                $ini[ $name ] = $object->getValue();
                
            } elseif( $object instanceof Item\Section ) {
                
                // Section
                $ini[ $name ] = $object->toArray();
                
            } elseif( $object instanceof Item\Array ) {
                
                // Array
                $ini[ $name ] = array();
                
                // Gets the values
                $values       = $object->getValues();
                
                // Process each value
                foreach( $values as $value ) {
                    
                    // Stores the value
                    $ini[ $name ][] = $value->getValue();
                }
            }
        }
        
        // Ensures the items pointer is untouched
        reset( $this->_items );
        
        // Returns the INI array
        return $ini;
    }
    
    /**
     * Writes the INI values to a file
     * 
     * @param   string                      The name of the file to write
     * @param   string                      The path of the file to write (directory name)
     * @param   boolean                     Whether a call to the PHP exit() function must be added at the top of the file, in order to secures it
     * @return  void
     * @throws  Woops\Ini\File\Exception    If the directory does not exists
     * @throws  Woops\Ini\File\Exception    If the directory is not writeable
     * @throws  Woops\Ini\File\Exception    If the file is not writeable
     * @throws  Woops\Ini\File\Exception    If a write error occured
     */
    public function toFile( $fileName, $filePath, $phpExit = false )
    {
        // Checks if the path ends with a directory separator
        if( substr( $filePath, 0, -1 ) !== DIRECTORY_SEPARATOR ) {
            
            // Adds the directory separator to the end of the path
            $filePath .= DIRECTORY_SEPARATOR;
        }
        
        // Complete path to the file
        $fullPath = $filePath . $fileName;
        
        // Checks if the directory exists
        if( !file_exists( $filePath ) || !is_dir( $filePath ) ) {
            
            // Error - No such directory
            throw new File\Exception(
                'The directory does not exist (path: ' . $fullPath . ')',
                File\Exception::EXCEPTION_NO_DIR
            );
        }
        
        // If the file does not exist, checks if the directory is writeable
        if( !file_exists( $fullPath ) && !is_writeable( $filePath ) ) {
            
            // Error - Directory not writeable
            throw new File\Exception(
                'The directory is not writeable (path: ' . $filePath . ')',
                File\Exception::EXCEPTION_DIR_NOT_WRITEABLE
            );
        }
        
        // If the file exists, checks if it is writeable
        if( file_exists( $fullPath ) && !is_writeable( $fullPath ) ) {
            
            // Error - The file is not writeable
            throw new File\Exception(
                'The file is not writeable (path: ' . $fullPath . ')',
                File\Exception::EXCEPTION_FILE_NOT_WRITEABLE
            );
        }
        
        // Checks if the file must be secured
        if( $phpExit ) {
            
            // INI file content
            $content = '; WOOPS configuration file <?php exit(); ?>'
                     . self::$_str->NL
                     . self::$_str->NL
                     . ( string )$this;
            
        } else {
            
            // INI file content
            $content = ( string )$this;
        }
        
        // Tries to write the file
        if( !file_put_contents( $fullPath, $content ) ) {
            
            // Error - Cannot write the file
            throw new File\Exception(
                'Cannot write the ini file (path: ' . $fullPath . ')',
                File\Exception::EXCEPTION_WRITE_ERROR
            );
        }
    }
    
    /**
     * Creates a section item in the INI file
     * 
     * @param   string                  The name of the section item
     * @return  Woops\Ini\Item\Section  The new section object
     */
    public function newSectionItem( $name )
    {
        // Ensures the name is a string
        $name                  = ( string )$name;
        
        // Creates and stores the section object
        $this->_items[ $name ] = new Item\Section( $name );
        
        // Returns the section object
        return $this->_items[ $name ];
    }
    
    /**
     * Creates a value item in the INI file
     * 
     * @param   string                  The name of the value item
     * @param   string                  An optionnal value for the value item
     * @return  Woops\Ini\Item\Value    The new value object
     */
    public function newValueItem( $name, $value = '' )
    {
        // Ensures the name is a string
        $name                  = ( string )$name;
        
        // Creates and stores the value object
        $this->_items[ $name ] = new Item\Value( $name, $value );
        
        // Returns the value object
        return $this->_items[ $name ];
    }
    
    /**
     * Creates an array item in the INI file
     * 
     * @param   string                  The name of the array item
     * @param   string                  An optionnal array with values for the array item
     * @return  Woops\Ini\Item\Array    The new array object
     */
    public function newArrayItem( $name, array $values = array() )
    {
        // Ensures the name is a string
        $name                  = ( string )$name;
        
        // Creates and stores the array object
        $this->_items[ $name ] = new Item\Array( $name, $values );
        
        // Returns the array object
        return $this->_items[ $name ];
    }
    
    /**
     * Adds a section item in the INI file
     * 
     * @param   Woops\Ini\Item\Section  The section object to add
     * @return  void
     */
    public function addSectionItem( Item\Section $section )
    {
        $this->_items[ $section->getName() ] = $section;
    }
    
    /**
     * Adds a value item in the INI file
     * 
     * @param   Woops\Ini\Item\Value    The value object to add
     * @return  void
     */
    public function addValueItem( Item\Value $value )
    {
        $this->_items[ $value->getName() ] = $value;
    }
    
    /**
     * Adds an array item in the INI file
     * 
     * @param   Woops\Ini\Item\Array    The array object to add
     * @return  void
     */
    public function addArrayItem( Item\Array $array )
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
    public function isSection( $name )
    {
        return ( $this->itemExists( $name ) && $this->_items[ ( string )$name ] instanceof Item\Section ) ? true : false;
    }
    
    /**
     * Checks if an item in the INI file is an INI value
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item is a value, otherwise false
     */
    public function isValue( $name )
    {
        return ( $this->itemExists( $name ) && $this->_items[ ( string )$name ] instanceof Item\Value ) ? true : false;
    }
    
    /**
     * Checks if an item in the INI file is an INI array
     * 
     * @param   string  The name of the item
     * @return  boolean True if the item is an array, otherwise false
     */
    public function isArray( $name )
    {
        return ( $this->itemExists( $name ) && $this->_items[ ( string )$name ] instanceof Item\Array ) ? true : false;
    }
    
    /**
     * Gets an item form the INI file
     * 
     * @param   string  The name of the item to get
     * @return  object  The item object (Woops\Ini\Item\Section, Woops\Ini\Item\Value or Woops\Ini\Item\Array) if it exists, otherwise NULL
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

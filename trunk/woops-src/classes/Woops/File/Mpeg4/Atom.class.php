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
 * Abstract for the MPEG-4 atoms
 * 
 * This abstract class is the base class for all MPEG-4 atom classes.
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class Box ( unsigned int( 32 ) boxtype, optional unsigned int( 8 )[ 16 ] extended_type )
 * { 
 *      unsigned int( 32 ) size;
 *      unsigned int( 32 ) type = boxtype;
 *      
 *      if( size == 1 ) {
 *          
 *          unsigned int( 64 ) largesize;
 *          
 *      } elseif( size == 0 ) {
 *          
 *          // Box extends to end of file
 *      }
 *      
 *      if( boxtype == 'uuid') {
 *          
 *          unsigned int( 8 )[ 16 ] usertype = extended_type;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4
 */
abstract class Woops_File_Mpeg4_Atom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    abstract public function __toString();
    abstract public function getLength();
    
    /**
     * The instance of the binary utilities class
     */
    protected static $_binUtils = NULL;
    
    /**
     * Wether the static variables are set or not
     */
    protected static $_hasStatic = false;
    
    /**
     * The atom type
     */
    protected $_type             = '';
    
    /**
     * Wether the atom has an extended length
     */
    protected $_extended         = false;
    
    /**
     * The parent atom, if any
     */
    protected $_parent           = NULL;
    
    /**
     * Class constructor
     * 
     * @return  NULL
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
     * Sets the needed static variables
     * 
     * @return  NULL
     */
    protected static function _setStaticVars()
    {
        // Gets the instance of the binary utilities class
        self::$_binUtils  = Woops_Binary_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Marks the atom as extended (length on 64 bits), or not
     * 
     * @param   boolean True if the atom is extended, otherwise false
     * @return  boolean
     */
    public function setExtended( $value = true )
    {
        // Sets the extended state
        $this->_extended = ( boolean )$value;
        
        return true;
    }
    
    /**
     * Checks if the atom is extended (length on 64 bits)
     * 
     * @return  boolean
     */
    public function isExtended()
    {
        // Returns the extended state
        return $this->_extended;
    }
    
    /**
     * Gets the atom type
     * 
     * @return  string  The atom type (4 chars)
     */
    public function getType()
    {
        // Returns the atom type
        return $this->_type;
    }
    
    /**
     * Gets the atom hierarchy
     * 
     * @return  array  An array with every parent atom to the current one (included)
     */
    public function getHierarchy()
    {
        // Checks for a parent atom
        if( is_object( $this->_parent ) ) {
            
            // Gets the hierarchy from the parent
            $hierarchy   = $this->_parent->getHierarchy();
            
            // Adds the current atom to the hierarchy
            $hierarchy[] = $this->_type;
            
        } else {
            
            // Top level atom
            $hierarchy = array( $this->_type );
        }
        
        // Return the atom hierarchy
        return $hierarchy;
    }
}

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
namespace Woops\Mpeg4;

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
 * @package     Woops.Mpeg4
 */
abstract class Atom extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    abstract public function getLength();
    
    /**
     * Whether the static variables are set or not
     */
    protected static $_hasStatic = false;
    
    /**
     * The atom type
     */
    protected $_type             = '';
    
    /**
     * Whether the atom has an extended length
     */
    protected $_extended         = false;
    
    /**
     * The parent atom, if any
     */
    protected $_parent           = NULL;
    
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

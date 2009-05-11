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
namespace Woops\Ini\Item;

/**
 * INI section class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Ini.Item
 */
class Section extends \Woops\Ini\File
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The name of the section
     */
    protected $_name = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The name of the section item
     * @return  void
     */
    public function __construct( $name )
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Stores the section name
        $this->_name = ( string )$name;
    }
    
    /**
     * Returns the INI section as a string
     * 
     * @return  The INI section, in the INI format
     */
    public function __toString()
    {
        // Storage
        $section = '[' . $this->_name . ']';
        
        // Process each item
        foreach( $this->_items as $name => $object ) {
            
            // Adds the current item
            $section .= self::$_str->NL . ( string )$object;
        }
        
        // Returns the section
        return $section;
    }
    
    /**
     * Creates a section item in the INI file
     * 
     * @param   string                              The name of the section item
     * @return  Woops\Ini\Item\Section              The new section object
     * @throws  Woops\Ini\Item\Section\Exception    Always as an INI section cannot contains another section
     */
    public function newSectionItem( $name )
    {
        throw new Section\Exception(
            'An INI section cannot contains another section',
            Section\Exception::EXCEPTION_NESTED_SECTION
        );
    }
    
    /**
     * Adds a section item in the INI file
     * 
     * @param   Woops\Ini\Item\Section              The section object to add
     * @return  void
     * @throws  Woops\Ini\Item\Section\Exception    Always as an INI section cannot contains another section
     */
    public function addSectionItem( Section $section )
    {
        throw new Section\Exception(
            'An INI section cannot contains another section',
            Section\Exception::EXCEPTION_NESTED_SECTION
        );
    }
    
    /**
     * Gets the name of the section item
     * 
     * @return  string  The name of the section item
     */
    public function getName()
    {
        return $this->_name;
    }
}

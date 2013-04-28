<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * INI section class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Ini.Item
 */
class Woops_Ini_Item_Section extends Woops_Ini_File
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
     * @return  Woops_Ini_Item_Section              The new section object
     * @throws  Woops_Ini_Item_Section_Exception    Always as an INI section cannot contains another section
     */
    public function newSectionItem( $name )
    {
        throw new Woops_Ini_Item_Section_Exception(
            'An INI section cannot contains another section',
            Woops_Ini_Item_Section_Exception::EXCEPTION_NESTED_SECTION
        );
    }
    
    /**
     * Adds a section item in the INI file
     * 
     * @param   Woops_Ini_Item_Section              The section object to add
     * @return  void
     * @throws  Woops_Ini_Item_Section_Exception    Always as an INI section cannot contains another section
     */
    public function addSectionItem( Woops_Ini_Item_Section $section )
    {
        throw new Woops_Ini_Item_Section_Exception(
            'An INI section cannot contains another section',
            Woops_Ini_Item_Section_Exception::EXCEPTION_NESTED_SECTION
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

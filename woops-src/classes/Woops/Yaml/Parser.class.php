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
 * YAML parser class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Yaml
 */
class Woops_Yaml_Parser extends Woops_Core_Object
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
     * The instance of the string utilities class
     */
    protected static $_str     = NULL;
    
    /**
     * The YAML lines
     */
    protected $_lines = array();
    
    /**
     * Class constructor
     * 
     * @param   string  The YAML string
     * @return  void
     */
    public function __construct( $yaml )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Unifies the line breaks
        $yaml = self::$_str->unifyLineBreaks( $yaml );
        
        // Gets each line
        $this->_lines = explode( self::$_str->NL, $yaml );
        
        // Process each line
        foreach( $this->_lines as $key => $value ) {
            
            // Checks for a tabulation character
            if( strchr( $line, self::$_str->TAB ) ) {
                
                // Tabulation characters are not allowed
                throw new Woops_Yaml_Parser_Exception(
                    'Found a tab character at line ' . $key + 1 . '. Tab characters are not allowed in YAML',
                    Woops_Yaml_Parser_Exception::EXCEPTION_TAB_CHAR
                );
            }
        }
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = Woops_Number_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
}

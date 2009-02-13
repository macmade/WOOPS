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
 * INI file parser
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Ini
 */
class Woops_File_Ini_Parser
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
    private static $_str       = NULL;
    
    /**
     * The path to the INI file
     */
    protected $_filePath       = '';
    
    /**
     * The INI configuration values
     */
    protected $_values         = array();
    
    /**
     * Class constructor
     * 
     * @param   string  The path to the INI file to parse
     * @return  NULL
     */
    public function __construct( $path )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Checks if the file exists
        if( !file_exists( $path ) ) {
            
            // Error - The file does not exists
            throw new Woops_File_Ini_Parser_Exception(
                '',
                Woops_File_Ini_Parser_Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !is_readable( $path ) ) {
            
            // Error - The file is not readable
            throw new Woops_File_Ini_Parser_Exception(
                '',
                Woops_File_Ini_Parser_Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        // Stores the file path
        $this->_filePath = $path;
        
        // Parses the INI file
        $this->_parseFile();
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  NULL
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = Woops_String_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Parses the INI file
     * 
     * @return  NULL
     */
    protected function _parseFile()
    {
        // Gets each line of the INI file
        $lines    = file( $this->_filePath );
        
        // No active section at the moment
        $section  = '';
        
        // Storage for the JavaDoc-like comments
        $comments = array();
        
        // Process each line of the file
        foreach( $lines as &$line ) {
            
            // Storage for the matches (preg_match)
            $matches = array();
            
            // Checks if the current line is a blank one
            if( !trim( $line ) ) {
                
                // Resets the comments storage array
                $comments = array();
            }
            
            // Checks if the current line defines is a comment
            if( preg_match( '/^\s*;\s*(.*)$/', $line, $matches ) ) {
                
                // Stores the current comment
                $comments[] = trim( $matches[ 1 ] );
            }
            
            // Checks if the current line defines a section
            if( preg_match( '/^\s*\[([^\]]+)\]/', $line, $matches ) ) {
                
                // Name of the section
                $section = $matches[ 1 ];
                
                // Creates the storage array for the section
                $this->_values[ $section ] = array();
                
                // Process the next line
                continue;
            }
            
            // Checks if the current lines defines a value
            if( preg_match( '/^\s*([^;=\s]+)\s*=\s+([^;\s]+)/', $line, $matches ) ) {
                
                // Gets the variable name and its value
                $key   = $matches[ 1 ];
                $value = $matches[ 2 ];
                
                // Support for 'On'/'Off' values, which will be convert to boolean values
                if( $value === 'Off' ) {
                    
                    // Off - Converts to false
                    $value = false;
                    
                } elseif( $value === 'On' ) {
                    
                    // Off - Converts to true
                    $value = true;
                }
                
                // Checks if we are in a section or not
                if( $section ) {
                    
                    // Checks if the variable name represents an array
                    if( substr( $key, -2 ) === '[]' ) {
                        
                        // Gets only the variable name, without the '[]'
                        $key = substr( $key, 0, -2 );
                        
                        // Checks if a value has already been added for that variable
                        if( !isset( $this->_values[ $section ][ $key ] ) ) {
                            
                            // Creates the storage array
                            $this->_values[ $section ][ $key ] = array(
                                'value'    => array(),
                                'comments' => $comments
                            );
                            
                            // Resets the comments
                            $comments = array();
                        }
                        
                        // Adds the variable value
                        $this->_values[ $section ][ $key ][ 0 ][] = $value;
                        
                    } else {
                        
                        // Adds the variable value
                        $this->_values[ $section ][ $key ] = array(
                            'value'    => $value,
                            'comments' => $comments
                        );
                        
                        // Resets the comments
                        $comments = array();
                    }
                    
                } else {
                    
                    // Checks if the variable name represents an array
                    if( substr( $key, -2 ) === '[]' ) {
                        
                        // Gets only the variable name, without the '[]'
                        $key = substr( $key, 0, -2 );
                        
                        // Checks if a value has already been added for that variable
                        if( !isset( $this->_values[ $key ] ) ) {
                            
                            // Creates the storage array
                            $this->_values[ $key ] = array(
                                'value'    => array(),
                                'comments' => $comments
                            );
                            
                            // Resets the comments
                            $comments = array();
                        }
                        
                        // Adds the variable value
                        $this->_values[ $key ][ 0 ][] = $value;
                        
                    } else {
                        
                        // Adds the variable value
                        $this->_values[ $key ] = array(
                            'value'    => $value,
                            'comments' => $comments
                        );
                        
                        // Resets the comments
                        $comments = array();
                    }
                }
            }
        }
    }
}

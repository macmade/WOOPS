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
 * INI file parser
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Ini
 */
class Parser extends \Woops\Core\Object
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
     * The INI file object
     */
    protected $_ini            = NULL;
    
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
     * @return  void
     * @see     _parseFile
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
            throw new Parser\Exception(
                'The INI file does not exists (path: ' . $path . ')',
                Parser\Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !is_readable( $path ) ) {
            
            // Error - The file is not readable
            throw new Parser\Exception(
                'The INI file is not readable (path: ' . $path . ')',
                Parser\Exception::EXCEPTION_FILE_NOT_READABLE
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
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = \Woops\Helpers\StringUtilities::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Parses the INI file
     * 
     * @return  void
     * @see     _processComments
     */
    protected function _parseFile()
    {
        // Gets each line of the INI file
        $lines      = file( $this->_filePath );
        
        // No active section at the moment
        $section    = '';
        
        // Storage for the JavaDoc-like comments
        $comments   = array();
        
        // Creates an INI file object
        $this->_ini = new File();
        
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
                $section                   = $matches[ 1 ];
                
                // Creates the storage array for the section
                $this->_values[ $section ] = array();
                
                // Creates the section object
                $this->_ini->newSectionItem( $section );
                
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
                                'comments' => $this->_processComments( $comments )
                            );
                            
                            // Creates the array object
                            $this->_ini->getItem( $section )->newArrayValueItem( $key );
                            
                            // Resets the comments
                            $comments = array();
                        }
                        
                        // Adds the variable value
                        $this->_values[ $section ][ $key ][ 'value' ][] = $value;
                        $this->_ini->getItem( $section )->getItem( $key )->addValue( $value );
                        
                    } else {
                        
                        // Adds the variable value
                        $this->_values[ $section ][ $key ] = array(
                            'value'    => $value,
                            'comments' => $this->_processComments( $comments )
                        );
                        
                        // Creates the value object
                        $this->_ini->getItem( $section )->newValueItem( $key, $value );
                        
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
                                'comments' => $this->_processComments( $comments )
                            );
                            
                            // Creates the array object
                            $this->_ini->newArrayValueItem( $key );
                            
                            // Resets the comments
                            $comments = array();
                        }
                        
                        // Adds the variable value
                        $this->_values[ $key ][ 'value' ][] = $value;
                        $this->_ini->getItem( $key )->addValue( $value );
                        
                    } else {
                        
                        // Adds the variable value
                        $this->_values[ $key ] = array(
                            'value'    => $value,
                            'comments' => $this->_processComments( $comments )
                        );
                        
                        // Creates the value object
                        $this->_ini->newValueItem( $key, $value );
                        
                        // Resets the comments
                        $comments = array();
                    }
                }
            }
        }
    }
    
    /**
     * Process raw INI comments, to check for JavaDoc-like instructions
     * 
     * @param   array   An array with lines of raw INI comments
     * @return  array   An array with the processed comments
     */
    protected function _processComments( array $rawComments )
    {
        // Storage array
        $comments = array();
        
        // Process each comment line
        foreach( $rawComments as $comment ) {
            
            // Checks if we are reading a JavaDoc-like instruction or a normal comment
            if( substr( $comment, 0, 1 ) !== '@' ) {
                
                // Checks if the comment has to be interpreted as a title, or description (which can be multiline)
                if( !isset( $comments[ 'title' ] ) ) {
                    
                    // Adds the title
                    $comments[ 'title' ] = $comment;
                    
                } elseif( !isset( $comments[ 'description' ] ) ) {
                    
                    // Starts the description
                    $comments[ 'description' ] = $comment;
                    
                } else {
                    
                    // Checks if we must add a new line character
                    if( substr( $comments[ 'description' ], -1 ) === '.' ) {
                        
                        // Adds the current line to the existing description
                        $comments[ 'description' ] .= self::$_str->NL . $comment;
                        
                    } else {
                        
                        // Adds the current line to the existing description
                        $comments[ 'description' ] .= ' ' . $comment;
                    }
                }
                
                // Process the nex line
                continue;
            }
            
            // Checks if we are reading a @type instruction
            if( substr( $comment, 0, 5 ) == '@type' ) {
                
                // Sets the type
                $comments[ 'type' ] = trim( substr( $comment, 5 ) );
            }
            
            // Checks if we are reading a @required instruction
            if( substr( $comment, 0, 9 ) == '@required' ) {
                
                // Sets the required state
                $comments[ 'required' ] = true;
            }
            
            // Checks if we are reading an @option instruction
            if( substr( $comment, 0, 7 ) == '@option' ) {
                
                // Checks if options were already added or not
                if( !isset( $comments[ 'options' ] ) ) {
                    
                    // Creates the storage array for the options
                    $comments[ 'options' ] = array();
                }
                
                // Adds the option
                $comments[ 'options' ][] = trim( substr( $comment, 7 ) );
            }
        }
        
        // Returns the processed comments
        return $comments;
    }
    
    /**
     * Gets an array with the INI values
     * 
     * @return  array   An array with the INI values
     */
    public function getIniArray()
    {
        return $this->_values;
    }
    
    /**
     * Gets the INI file object
     * 
     * @return  Woops\Ini\File The INI file object
     */
    public function getIniObject()
    {
        return $this->_ini;
    }
}

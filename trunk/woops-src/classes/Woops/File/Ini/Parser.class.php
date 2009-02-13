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
     * 
     */
    protected $_filePath       = '';
    
    /**
     * 
     */
    protected $_values         = array();
    
    /**
     * 
     */
    public function __construct( $path )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        if( !file_exists( $path ) ) {
            
            throw new Woops_File_Ini_Parser_Exception(
                '',
                Woops_File_Ini_Parser_Exception::EXCEPTION_NO_FILE
            );
        }
        
        if( !is_readable( $path ) ) {
            
            throw new Woops_File_Ini_Parser_Exception(
                '',
                Woops_File_Ini_Parser_Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        $this->_filePath = $path;
        
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
     * 
     */
    protected function _parseFile()
    {
        $lines    = file( $this->_filePath );
        $section  = '';
        $comments = array();
        
        foreach( $lines as &$line ) {
            
            $matches = array();
            
            if( !trim( $line ) ) {
                
                $comments = array();
            }
            
            if( preg_match( '/^\s*;\s*(.*)$/', $line, $matches ) ) {
                
                $comments[] = trim( $matches[ 1 ] );
            }
            
            if( preg_match( '/^\s*\[([^\]]+)\]/', $line, $matches ) ) {
                
                $section = $matches[ 1 ];
                $this->_values[ $section ] = array();
                continue;
            }
            
            if( preg_match( '/^\s*([^;=\s]+)\s*=\s+([^;\s]+)/', $line, $matches ) ) {
                
                $key   = $matches[ 1 ];
                $value = $matches[ 2 ];
                
                if( $value === 'Off' ) {
                    
                    $value = false;
                    
                } elseif( $value === 'On' ) {
                    
                    $value = true;
                }
                
                if( $section ) {
                    
                    if( substr( $key, -2 ) === '[]' ) {
                        
                        $key = substr( $key, 0, -2 );
                        
                        if( !isset( $this->_values[ $section ][ $key ] ) ) {
                            
                            $this->_values[ $section ][ $key ] = array(
                                'value'    => array(),
                                'comments' => $comments
                            );
                            
                            $comments = array();
                        }
                        
                        $this->_values[ $section ][ $key ][ 0 ][] = $value;
                        
                    } else {
                        
                        $this->_values[ $section ][ $key ] = array(
                            'value'    => $value,
                            'comments' => $comments
                        );
                        
                        $comments = array();
                    }
                    
                } else {
                    
                    if( substr( $key, -2 ) === '[]' ) {
                        
                        $key = substr( $key, 0, -2 );
                        
                        if( !isset( $this->_values[ $key ] ) ) {
                            
                            $this->_values[ $key ] = array(
                                'value'    => array(),
                                'comments' => $comments
                            );
                            
                            $comments = array();
                        }
                        
                        $this->_values[ $key ][ 0 ][] = $value;
                        
                    } else {
                        
                        $this->_values[ $key ] = array(
                            'value'    => $value,
                            'comments' => $comments
                        );
                        
                        $comments = array();
                    }
                }
            }
        }
    }
}

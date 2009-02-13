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
 * INI file writer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Ini
 */
class Woops_File_Ini_Writer
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
    protected $_ini            = array();
    
    /**
     * Class constructor
     * 
     * The class constructor is private as all methods from this class are
     * static.
     * 
     * @return  NULL
     */
    private function __construct( array $ini )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        $this->_ini = $ini;
    }
    
    /**
     * 
     */
    public function __toString()
    {
        $ini = '';
        
        foreach( $this->_ini as $key => $value ) {
            
            if( is_array( $value ) ) {
                
                $ini .= '[' . $key . ']' . self::$_str->NL;
                
                foreach( $value as $sectionKey => $sectionValue ) {
                    
                    if( is_array( $sectionValue ) ) {
                        
                        foreach( $sectionValue as $arrayValue ) {
                            
                            $ini .= $sectionKey . '[] = ' . $arrayValue . self::$_str->NL;
                        }
                        
                    } else {
                        
                        $ini .= $sectionKey . ' = ' . $sectionValue . self::$_str->NL;
                    }
                }
                
            } else {
                
                $ini .= $key . ' = ' . $value . self::$_str->NL;
            }
        }
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
    public function toFile( $filePath )
    {
        if( !file_exists( $filePath ) || !is_file( $filePath ) ) {
            
            throw new Woops_File_Ini_Writer_Exception(
                'The specified file does not exist (path: ' . $filePath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_NO_FILE
            );
        }
        
        if( !is_writeable( $filePath ) ) {
            
            throw new Woops_File_Ini_Writer_Exception(
                'The specified file is not writeable (path: ' . $filePath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_FILE_NOT_WRITEABLE
            );
        }
        
        if( !file_put_contents( $filePath, ( string )$this ) ) {
            
            throw new Woops_File_Ini_Writer_Exception(
                'Cannot write the ini file (path: ' . $filePath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_WRITE_ERROR
            );
        }
    }
}

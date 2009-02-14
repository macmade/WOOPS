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
    protected static $_str     = NULL;
    
    /**
     * The INI values
     */
    protected $_ini            = array();
    
    /**
     * Class constructor
     * 
     * @param   array   An array with the INI values (may have sections, as sub-arrays)
     * @return  NULL
     */
    public function __construct( array $ini )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Stores the INI values
        $this->_ini = $ini;
    }
    
    /**
     * Gets the INI values as an INI file
     * 
     * @return  string  An INI file
     */
    public function __toString()
    {
        // Storage
        $ini = '';
        
        // Process each value
        foreach( $this->_ini as $key => $value ) {
            
            // Checks if we have a sub-array, meaning we have a section
            if( is_array( $value ) ) {
                
                // Adds the section name
                $ini .= '[' . $key . ']' . self::$_str->NL;
                
                // Process each section value
                foreach( $value as $sectionKey => $sectionValue ) {
                    
                    // Checks if the value is an array
                    if( is_array( $sectionValue ) ) {
                        
                        // Process each sub-value
                        foreach( $sectionValue as $arrayValue ) {
                            
                            // Adds the array value
                            $ini .= $sectionKey . '[] = ' . $arrayValue . self::$_str->NL;
                        }
                        
                    } else {
                        
                        // Adds the section value
                        $ini .= $sectionKey . ' = ' . $sectionValue . self::$_str->NL;
                    }
                }
                
            } else {
                
                // Adds the value
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
     * Writes the INI values to a file
     * 
     * @param   string  The name of the file to write
     * @param   string  The path of the file to write (directory name)
     * @return  NULL
     * @throws  Woops_File_Ini_Writer_Exception If the directory does not exists
     * @throws  Woops_File_Ini_Writer_Exception If the directory is not writeable
     * @throws  Woops_File_Ini_Writer_Exception If the file is not writeable
     * @throws  Woops_File_Ini_Writer_Exception If a write error occured
     */
    public function toFile( $fileName, $filePath )
    {
        // Checks if the path ends with a directory separator
        if( substr( $filePath, 0, -1 ) !== DIRECTORY_SEPARATOR ) {
            
            // Adds the directory separator to the end of the path
            $filePath .= DIRECTORY_SEPARATOR;
        }
        
        // Complete path to the file
        $fullPath = $filePath . $fileName;
        
        // Checks if the directory exists
        if( !file_exists( $filePath ) || !is_dir( $filePath ) ) {
            
            // Error - No such directory
            throw new Woops_File_Ini_Writer_Exception(
                'The directory does not exist (path: ' . $fullPath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_NO_DIR
            );
        }
        
        // If the file does not exist, checks if the directory is writeable
        if( !file_exists( $filePath ) && !is_writeable( $filePath ) ) {
            
            // Error - Directory not writeable
            throw new Woops_File_Ini_Writer_Exception(
                'The directory is not writeable (path: ' . $filePath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_DIR_NOT_WRITEABLE
            );
        }
        
        // If the file exists, checks if it is writeable
        if( file_exists( $filePath ) && !is_writeable( $filePath ) ) {
            
            // Error - The file is not writeable
            throw new Woops_File_Ini_Writer_Exception(
                'The file is not writeable (path: ' . $fullPath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_FILE_NOT_WRITEABLE
            );
        }
        
        // Tries to write the file
        if( !file_put_contents( $filePath, ( string )$this ) ) {
            
            // Error - Cannot write the file
            throw new Woops_File_Ini_Writer_Exception(
                'Cannot write the ini file (path: ' . $fullPath . ')',
                Woops_File_Ini_Writer_Exception::EXCEPTION_WRITE_ERROR
            );
        }
    }
}

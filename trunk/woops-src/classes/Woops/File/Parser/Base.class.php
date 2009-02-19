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
 * Abstract class for the file parsers
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Parser
 */
abstract class Woops_File_Parser_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The abstract method used to parse the file
     * 
     * @return  void
     */
    abstract protected function _parseFile();
    
    /**
     * The instance of the binary utilities class
     */
    protected static $_binUtils  = NULL;
    
    /**
     * Wether the static variables are set or not
     */
    protected static $_hasStatic = false;
    
    /**
     * The PHP file handler
     */
    protected $_fileHandle       = NULL;
    
    /**
     * The file path
     */
    protected $_filePath         = '';
    
    /**
     * Class constructor
     * 
     * @param   string                              The location of the file to parse
     * @return  void
     * @throws  Woops_File_Parser_Base_Exception    If the file does not exist
     * @throws  Woops_File_Parser_Base_Exception    If the file is not readable
     * @throws  Woops_File_Parser_Base_Exception    If PHP isn't able to open a file handle
     */
    public function __construct( $file )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Checks if the requested file exists
        if( !file_exists( $file ) ) {
            
            // File does not exist
            throw new Woops_File_Parser_Base_Exception(
                'The requested file ' . $file . ' does not exist.',
                Woops_File_Parser_Base_Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the requested file can be read
        if( !is_readable( $file ) ) {
            
            // Unreadable file
            throw new Woops_File_Parser_Base_Exception(
                'The requested file ' . $file . ' is not readable.',
                Woops_File_Parser_Base_Exception::EXCEPTION_UNREADABLE
            );
        }
        
        // Opens a binary file hander
        $this->_fileHandle = fopen( $file, 'rb' );
        
        // Checks the file handler
        if( !$this->_fileHandle ) {
            
            // Invalid file handler
            throw new Woops_File_Parser_Base_Exception(
                'Cannot open requested file ' . $file . '.',
                Woops_File_Parser_Base_Exception::EXCEPTION_INVALID_HANDLER
            );
        }
        
        // Stores the file path
        $this->_filePath = $file;
        
        // Parses the file
        $this->_parseFile();
        
        // Closes the file handle
        fclose( $this->_fileHandle );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    protected static function _setStaticVars()
    {
        // Gets the instance of the binary utilities class
        self::$_binUtils  = Woops_Binary_Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Reads bytes from the file handler
     * 
     * @param   int     The number of bytes to read
     * @return  string  The bytes from the file
     */
    protected function _read( $length )
    {
        return fread( $this->_fileHandle, $length );
    }
}

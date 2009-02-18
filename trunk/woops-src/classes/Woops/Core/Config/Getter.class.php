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
 * WOOPS configuration getter class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Config
 */
final class Woops_Core_Config_Getter implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The WOOPS configuration array
     */
    private $_conf            = array();
    
    /**
     * The configuration options for the WOOPS modules
     */
    private $_modConf         = array();
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Config_Getter_Exception  If the configuration file does not exist
     * @throws  Woops_Core_Config_Getter_Exception  If the configuration file is not writeable
     */
    private function __construct()
    {
        // Gets the path to the WOOPS configuration file
        $confFile = Woops_Core_Env_Getter::getInstance()->getPath( 'config.ini.php' );
        
        // Checks if the file exists
        if( !$confFile ) {
            
            // Error - No configuration file
            throw new Woops_Core_Config_Getter_Exception(
                'The WOOPS configuration file does not exist',
                Woops_Core_Config_Getter_Exception::EXCEPTION_NO_CONFIG_FILE
            );
        }
        
        // Checks if the file is readable
        if( !is_readable( $confFile ) ) {
            
            // Error - The configuration file is not readable
            throw new Woops_Core_Config_Getter_Exception(
                'The WOOPS configuration file is not readable',
                Woops_Core_Config_Getter_Exception::EXCEPTION_CONFIG_FILE_NOT_READABLE
            );
        }
        
        // Gets the configuration values from the file
        $this->_conf = parse_ini_file( $confFile, true );
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets a section from the WOOPS configuration
     * 
     * @param   string  The name of the section
     * @return  array   The section (empty array if it does not exist)
     */
    public function __get( $name )
    {
        return ( isset( $this->_conf[ $name ] ) ) ? clone( $this->_conf[ $name ] ) : array();
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Core_Env_Getter   The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * Reads a configuration file from a WOOPS module
     * 
     * @param   string  The name of the module
     * @return  void
     * @throws  Woops_Core_Config_Getter_Exception  If the configuration file is not readable
     */
    private function _loadModuleConf( $name )
    {
        // Gets the path to the module's configuration file
        $modConf = Woops_Core_Module_Manager::getInstance()->getModulePath( $name ) . 'config.ini.php';
        
        // Checks if the file exists
        if( file_exists( $modConf ) ) {
            
            // Checks if the file is readable
            if( !is_readable( $modConf ) ) {
                
                // Error - The configuration file is not readable
                throw new Woops_Core_Config_Getter_Exception(
                    'The WOOPS configuration file for module \'' . $name . '\' is not readable',
                    Woops_Core_Config_Getter_Exception::EXCEPTION_CONFIG_FILE_NOT_READABLE
                );
            }
            
            // Gets the configuration values from the file
            $this->_modConf[ $name ] = parse_ini_file( $modConf, true );
        }
        
        // Checks if the file was parsed
        if( !is_array( $this->_modConf[ $name ] ) ) {
            
            // No - Creates an empty array
            $this->_modConf[ $name ] = array();
        }
    }
    
    /**
     * Gets a WOOPS configuration variable
     * 
     * @param   string  The name of the section
     * @param   string  The name of the configuration variable
     * @return  mixed   The value of the configuration variable
     */
    public function getVar( $section, $key )
    {
        return ( isset( $this->_conf[ $section ][ $key ] ) ) ? $this->_conf[ $section ][ $key ] : false;
    }
    
    /**
     * Deletes a variable from the WOOPS configuration
     * 
     * This can be useful for security reasons, like database connection
     * parameters, passwords, etc.
     * 
     * @param   string  The name of the section
     * @param   string  The name of the configuration variable
     * @return  mixed   The value of the configuration variable
     */
    public function deleteVar( $section, $key )
    {
        unset( $this->_conf[ $section ][ $key ] );
    }
    
    /**
     * Gets a configuration variable for a WOOPS module
     * 
     * @param   string  The name of the module
     * @param   string  The name of the section
     * @param   string  The name of the configuration variable
     * @return  mixed   The value of the configuration variable
     */
    public function getModuleVar( $name, $section, $key )
    {
        if( !isset( $this->_modConf[ $name ] ) ) {
            
            $this->_loadModuleConf( $name );
        }
        
        return ( isset( $this->_modConf[ $name ][ $section ][ $key ] ) ) ? $this->_modConf[ $name ][ $section ][ $key ] : false;
    }
    
    /**
     * Deletes a variable from a module's configuration
     * 
     * This can be useful for security reasons, like database connection
     * parameters, passwords, etc.
     * 
     * @param   string  The name of the module
     * @param   string  The name of the section
     * @param   string  The name of the configuration variable
     * @return  mixed   The value of the configuration variable
     */
    public function deleteModuleVar( $name, $section, $key )
    {
        unset( $this->_modConf[ $name ][ $section ][ $key ] );
    }
}

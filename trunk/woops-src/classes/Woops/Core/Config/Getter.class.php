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
     * @return NULL
     */
    private function __construct()
    {
        $confFile = Woops_Core_Env_Getter::getInstance()->getPath( 'config.ini.php' );
        
        if( !$confFile ) {
            
            throw new Woops_Core_Config_Getter_Exception(
                'The WOOPS configuration file does not exist',
                Woops_Core_Config_Getter_Exception::EXCEPTION_NO_CONFIG_FILE
            );
        }
        
        if( !is_readable( $confFile ) ) {
            
            throw new Woops_Core_Config_Getter_Exception(
                'The WOOPS configuration file is not readable',
                Woops_Core_Config_Getter_Exception::EXCEPTION_CONFIG_FILE_NOT_READABLE
            );
        }
        
        $this->_conf = parse_ini_file( $confFile, true );
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
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
     * 
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
     * 
     */
    private function _loadModuleConf( $name )
    {
        $modConf = Woops_Core_Module_Manager::getInstance()->getModulePath( $name ) . 'config.ini.php';
        
        if( file_exists( $modConf ) ) {
            
            if( !is_readable( $modConf ) ) {
                
                throw new Woops_Core_Config_Getter_Exception(
                    'The WOOPS configuration file for module \'' . $name . '\' is not readable',
                    Woops_Core_Config_Getter_Exception::EXCEPTION_CONFIG_FILE_NOT_READABLE
                );
            }
            
            $this->_modConf[ $name ] = parse_ini_file( $modConf, true );
        }
        
        if( !is_array( $this->_modConf[ $name ] ) ) {
            
            $this->_modConf[ $name ] = array();
        }
    }
    
    /**
     * 
     */
    public function getVar( $section, $key )
    {
        return ( isset( $this->_conf[ $section ][ $key ] ) ) ? $this->_conf[ $section ][ $key ] : false;
    }
    
    /**
     * 
     */
    public function deleteVar( $section, $key )
    {
        unset( $this->_conf[ $section ][ $key ] );
    }
    
    /**
     * 
     */
    public function getModuleVar( $name, $section, $key )
    {
        if( !isset( $this->_modConf[ $name ] ) ) {
            
            $this->_loadModuleConf( $name );
        }
        
        return ( isset( $this->_modConf[ $name ][ $section ][ $key ] ) ) ? $this->_modConf[ $name ][ $section ][ $key ] : false;
    }
    
    /**
     * 
     */
    public function deleteModuleVar( $name, $section, $key )
    {
        unset( $this->_modConf[ $name ][ $section ][ $key ] );
    }
}

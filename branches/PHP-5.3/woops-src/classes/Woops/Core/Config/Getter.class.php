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
namespace Woops\Core\Config;

/**
 * WOOPS configuration getter class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Config
 */
final class Getter extends \Woops\Core\Object implements \Woops\Core\Singleton\Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The environment object
     */
    private $_env             = NULL;
    
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
     * If an error occurs, meaning the configuration file is not available,
     * this method will simply prints the error message.
     * No trigger_error, nor exception, as this will result in an infinite
     * loop between the core classes.
     * 
     * @return  void
     */
    private function __construct()
    {
        // Gets the instance of the environment object
        $this->_env = \Woops\Core\Env\Getter::getInstance();
        
        // Gets the path to the WOOPS configuration file
        $confFile  = $this->_env->getPath( 'config/woops.ini.php' );
        
        // Checks if the file exists
        if( !$confFile ) {
            
            // Gets the path to the default WOOPS configuration file
            $confFile  = $this->_env->getSourcePath( 'config.ini.php' );
        }
        
        // Checks if the file exists
        if( !$confFile ) {
            
            // Error - No configuration file
            self::_error( 'The WOOPS configuration file does not exist' );
        }
        
        // Checks if the file is readable
        if( !is_readable( $confFile ) ) {
            
            // Error - The configuration file is not readable
            self::_error( 'The WOOPS configuration file is not readable' );
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
     * @throws  Woops\Core\Singleton\Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new \Woops\Core\Singleton\Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            \Woops\Core\Singleton\Exception::EXCEPTION_CLONE
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
     * Creates an error message
     * 
     * This function will abort the current script, and writes the passed
     * error message.
     * As other core classes have to use the configuration class, we cannot use
     * exceptions, nor the trigger_error function, so we have to display
     * error by ourselves.
     * 
     * @param   string  The error message to display.
     * @return  void
     */
    private static function _error( $message )
    {
        // Gets the debug backtrace
        $backTrace = debug_backtrace();
        
        // Gets the last method call
        $fault     = $backTrace[ 1 ];
        
        // We'll try to reproduce the look of the PHP errors
        $error   = '<b>Fatal '
                 . __CLASS__
                 . ' error</b>: '
                 . $message
                 . ' in <b>'
                 . __FILE__
                 . '</b> on line <b>'
                 . $fault[ 'line' ]
                 . '</b>';
        
        // Checks if HTML error must be turned off
        if( !@ini_get( 'html_errors' ) ) {
            
            // Removes all HTML tags
            $error = chr( 10 ) . strip_tags( $error );
            
        } else {
            
            // Adds a line break before the error, as PHP does
            $error = '<br />' . $error;
        }
        
        //  Gets the prepend and append strings, if any
        $prepend = ( ini_get( 'error_prepend_string' ) ) ? ini_get( 'error_prepend_string' ) : '';
        $append  = ( ini_get( 'error_append_string' ) )  ? ini_get( 'error_append_string' )  : '';
        
        
        // Displays the PHP style error message and aborts the script
        print $prepend . $error . $append;
        exit();
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops\Core\Env\Getter   The unique instance of the class
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
     * @throws  Woops\Core\Config\Getter\Exception  If the configuration file is not readable
     */
    private function _loadModuleConf( $name )
    {
        // Gets the path to the module's configuration file
        $modConf = \Woops\Core\Module\Manager::getInstance()->getModulePath( $name ) . 'config.ini.php';
        
        // Checks if the file exists
        if( file_exists( $modConf ) ) {
            
            // Checks if the file is readable
            if( !is_readable( $modConf ) ) {
                
                // Error - The configuration file is not readable
                throw new Getter\Exception(
                    'The WOOPS configuration file for module \'' . $name . '\' is not readable',
                    Getter\Exception::EXCEPTION_CONFIG_FILE_NOT_READABLE
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

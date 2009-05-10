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
namespace Woops\Database;

/**
 * WOOPS database layer class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Database
 */
final class Layer extends \Woops\Core\Event\Dispatcher implements \Woops\Core\Singleton\Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance  = NULL;
    
    /**
     * The WOOPS configuration object
     */
    private $_conf             = NULL;
    
    /**
     * The registered database engines
     */
    private $_engines          = array();
    
    /**
     * The names of the registered database engines
     */
    private $_engineNames      = array();
    
    /**
     * The connected database engines
     */
    private $_connectedEngines = array();
    
    /**
     * The supported drivers for each engine
     */
    private $_drivers          = array();
    
    /**
     * The name of the default database engine
     */
    private $_defaultEngine    = '';
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     */
    private function __construct()
    {
        // Gets the instance of the configuration object
        $this->_conf          = \Woops\Core\Config\Getter::getInstance();
        
        // Stores the name of the default database engine
        $this->_defaultEngine = $this->_conf->getVar( 'database', 'engine' );
    }
    
    /**
     * Class destructor
     * 
     * This method will close the database connection on all loaded engines.
     * 
     * @return  void
     * @see     Woops\Core\Singleton\Interface::disconnect
     */
    public function __destruct()
    {
        // Process each database engine
        foreach( $this->_engines as $key => $value ) {
            
            // Checks if the engine is connected
            if( isset( $this->_connectedEngines[ $key ] ) ) {
                
                // Dispatch the event to the listeners
                $this->dispatchEventObject( new Layer\Event( Layer\Event::EVENT_ENGINE_DISCONNECT, $value ) );
                
                // Closes the connection
                $value->disconnect();
            }
        }
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
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops\Database\Layer    The unique instance of the class
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
     * Registers a class as a database engine
     * 
     * Note that the database engine class must implement the
     * Woops\Database\Engine\Interface interface.
     * 
     * @param   string  The name of the database engine
     * @param   string  The class of the database engine
     * @return  void
     * @throws  Woops\Database\Layer\Exception  If an engine with the same name is already registered
     * @throws  Woops\Database\Layer\Exception  If the engine class does not exists
     * @throws  Woops\Database\Layer\Exception  If the engine class does not implements the Woops\Database\Engine\Interface interface
     */
    public function registerDatabaseEngine( $name, $class )
    {
        // Checks for an engine with the same name
        if( isset( $this->_engines[ $name ] ) ) {
            
            // Engine already registered
            throw new Layer\Exception(
                'The engine \'' . $name . '\' is already registered',
                Layer\Exception::EXCEPTION_ENGINE_EXISTS
            );
        }
        
        // Checks for the engine class
        if( !class_exists( $class ) ) {
            
            // The engine class does not exist
            throw new Layer\Exception(
                'Cannot register unexisting class \'' . $class . '\' as a database engine',
                Layer\Exception::EXCEPTION_NO_ENGINE
            );
        }
        
        // Gets the interfaces
        $interfaces = class_implements( $class );
        
        // Checks if the engine class implements the database engine interface
        if( !isset( $interfaces[ 'Woops\Database\Engine\Interface' ] ) ) {
            
            // Invalid class
            throw new Layer\Exception(
                'Cannot register class \'' . $class . '\' as a database engine, since it does not implements the \'Woops\Database\Engine\Interface\' interface',
                Layer\Exception::EXCEPTION_INVALID_ENGINE_CLASS
            );
        }
        
        // Gets and stores the instance of the database engine class
        $this->_engines[ $name ]     = \Woops\Core\Class\Manager::getInstance()->getSingleton( $class );
        $this->_engineNames[ $name ] = true;
        
        // Gets the available drivers from the engine
        $this->_drivers[ $name ]     = $this->_engines[ $name ]->getAvailableDrivers();
        
        // Dispatch the event to the listeners
        $this->dispatchEventObject( new Layer\Event( Layer\Event::EVENT_ENGINE_REGISTER, $this->_engines[ $name ] ) );
    }
    
    /**
     * Gets a database engine
     * 
     * If no engine is specified, this method will return the default database
     * engine.
     * 
     * @param   string                          The name of the database engine
     * @return  object                          The database engine
     * @throws  Woops\Database\Layer\Exception  If the requested engine does not exist
     */
    public function getEngine( $name = '' )
    {
        // Checks for an engine name
        if( !$name ) {
            
            // Gets the default engine
            $name = $this->_defaultEngine;
        }
        
        // Checks if the engine exists
        if( !isset( $this->_engines[ $name ] ) ) {
            
            // The requested engine is not registered
            throw new Layer\Exception(
                'The engine \'' . $name . '\' is not a registered database engine',
                Layer\Exception::EXCEPTION_NO_ENGINE
            );
        }
        
        // Database parameters
        static $driver;
        static $host;
        static $port;
        static $username;
        static $password;
        static $database;
        static $tablePrefix;
        
        // Gets the configuration variables if needed
        if( !$driver ) {
            
            // Sets the default connection infos
            $driver      = $this->_conf->getVar( 'database', 'driver' );
            $host        = $this->_conf->getVar( 'database', 'host' );
            $port        = $this->_conf->getVar( 'database', 'port' );
            $username    = $this->_conf->getVar( 'database', 'user' );
            $password    = $this->_conf->getVar( 'database', 'password' );
            $database    = $this->_conf->getVar( 'database', 'database' );
            $tablePrefix = $this->_conf->getVar( 'database', 'tablePrefix' );
            
            // Security - Removes some configuration variables
            $this->_conf->deleteVar( 'database', 'user' );
            $this->_conf->deleteVar( 'database', 'password' );
            
            // Sets the WOOPS table prefix
            $tablePrefix = ( $tablePrefix ) ? ( string )$tablePrefix : '';
            
            // Checks the mandatory setting
            if( !$driver || !$host || !$database ) {
                
                // Error - Invalid configuration
                throw new Layer\Exception(
                    'The database settings are not properly configured',
                    Layer\Exception::EXCEPTION_BAD_CONFIGURATION
                );
            }
        }
        
        // Checks if the requested engine supports the database driver we are using
        if( !isset( $this->_drivers[ $name ][ $driver ] ) ) {
            
            // Error - The engine does not supports the database driver
            throw new Layer\Exception(
                'The engine \'' . $name . '\' does not support the driver \'' . $driver . '\'',
                Layer\Exception::EXCEPTION_DRIVER_NOT_SUPPORTED
            );
        }
        
        // Checks if the engine is connected or not
        if( !isset( $this->_connectedEngines[ $name ] ) ) {
            
            // Loads the engine
            $this->_engines[ $name ]->load( $driver, $host, $port, $database, $tablePrefix );
            
            // Dispatch the event to the listeners
            $this->dispatchEventObject( new Layer\Event( Layer\Event::EVENT_ENGINE_LOAD, $this->_engines[ $name ] ) );
            
            // Establish a connection with the database
            $this->_engines[ $name ]->connect( $username, $password );
            
            // Dispatch the event to the listeners
            $this->dispatchEventObject( new Layer\Event( Layer\Event::EVENT_ENGINE_CONNECT, $this->_engines[ $name ] ) );
            
            // Engine is connected
            $this->_connectedEngines[ $name ] = true;
        }
        
        // Returns the instance of the engine
        return $this->_engines[ $name ];
    }
    
    /**
     * Gets the class name of a database engine
     * 
     * If no engine is specified, this method will return the class name of the
     * default database engine.
     * 
     * @param   string                          The name of the database engine
     * @return  string                          The class name of the database engine
     * @throws  Woops\Database\Layer\Exception  If the requested engine does not exist
     */
    public function getEngineClass( $name = '' )
    {
        // Checks for an engine name
        if( !$name ) {
            
            // Gets the default engine
            $name = $this->_defaultEngine;
        }
        
        // Checks if the engine exists
        if( !isset( $this->_engines[ $name ] ) ) {
            
            // The requested engine is not registered
            throw new Layer\Exception(
                'The engine \'' . $name . '\' is not a registered database engine',
                Layer\Exception::EXCEPTION_NO_ENGINE
            );
        }
        
        // Returns the class name of the engine
        return get_class( $this->_engines[ $name ] );
    }
    
    /**
     * Checks if an engine is registered
     * 
     * @param   string  The name of the engine
     * @return  boolean True if the engine is registered, otherwise false
     */
    public function isRegisteredEngine( $name )
    {
        return isset( $this->_engines[ $name ] );
    }
    
    /**
     * Gets the list of the registered engines
     * 
     * @return  array   An array with the registered engine names, as keys
     */
    public function getRegisteredEngines()
    {
        return $this->_engineNames;
    }
}

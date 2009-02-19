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
 * WOOPS database layer class
 * 
 * The goal of the class is to provide WOOPS with the functionnalities of
 * PDO (PHP Data Object).
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Database
 */
final class Woops_Database_Layer implements Woops_Core_Singleton_Interface
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
     * The WOOPS cpnfiguration object
     */
    private $_conf            = NULL;
    
    /**
     * The registered database engines
     */
    private $_engines         = array();
    
    /**
     * The name of the default database engine
     */
    private $_defaultEngine   = '';
    
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
        $this->_conf          = Woops_Core_Config_Getter::getInstance();
        
        // Stores the name of the default database engine
        $this->_defaultEngine = $this->_conf->getVar( 'database', 'engine' );
    }
    
    /**
     * Class destructor
     * 
     * This method will close the database connection on all loaded engines.
     * 
     * @return  void
     */
    public function __destruct()
    {
        // Process each database engine
        foreach( $this->_engines as $key => $value ) {
            
            // Closes the connection
            $value->disconnect();
        }
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
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Database_Layer    The unique instance of the class
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
    public function registerDatabaseEngineClass( $name, $class )
    {
        if( isset( $this->_engines[ $name ] ) ) {
            
            throw new Woops_Database_Layer_Exception(
                'The engine \'' . $name . '\' is already registered',
                Woops_Database_Layer_Exception::EXCEPTION_ENGINE_EXISTS
            );
        }
        
        if( !class_exists( $class ) ) {
            
            throw new Woops_Database_Layer_Exception(
                'Cannot register unexisting class \'' . $class . '\' as a database engine',
                Woops_Database_Layer_Exception::EXCEPTION_NO_ENGINE
            );
        }
        
        $interfaces = class_implements( $class );
        
        if( !isset( $interfaces[ 'Woops_Database_Engine_Interface' ] ) ) {
            
            throw new Woops_Database_Layer_Exception(
                'Cannot register class \'' . $class . '\' as a database engine, since it does not implements the \'Woops_Database_Engine_Interface\' interface',
                Woops_Database_Layer_Exception::EXCEPTION_INVALID_ENGINE_CLASS
            );
        }
        
        eval( '$this->_engines[ $name ] = ' . $class . '::getInstance();' );
        
        static $driver;
        static $host;
        static $port;
        static $username;
        static $password;
        static $database;
        static $tablePrefix;
        
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
        }
        
        // Sets the WOOPS table prefix
        $tablePrefix = ( $tablePrefix ) ? ( string )$tablePrefix : '';
        
        $this->_engines[ $name ]->load( $driver, $host, $port, $database, $tablePrefix );
        $this->_engines[ $name ]->connect( $username, $password );
    }
    
    /**
     * 
     */
    public function getEngine( $name = '' )
    {
        if( !$name ) {
            
            $name = $this->_defaultEngine;
        }
        
        if( !isset( $this->_engines[ $name ] ) ) {
            
            throw new Woops_Database_Layer_Exception(
                'The engine \'' . $name . '\' is not a registered database engine',
                Woops_Database_Layer_Exception::EXCEPTION_NO_ENGINE
            );
        }
        
        return $this->_engines[ $name ];
    }
}

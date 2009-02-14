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

// Includes the WOOPS singleton interface.
// The WOOPS class manager can't auto-load it, since it's not available yet.
// So it has to be included manually. This should be the last time we use
// the require_once() function for a WOOPS class.
require_once( realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Singleton' ) . DIRECTORY_SEPARATOR . 'Interface.class.php' );

/**
 * WOOPS class manager
 * 
 * This class will handle every request to a class from this project,
 * by automatically loading the class file (thanx to the SPL).
 * 
 * If an error occurs, this class will simply prints the error message.
 * No trigger_error, nor exception, as this may cause strange PHP behavior,
 * because of the SPL autoload method.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Class
 */
final class Woops_Core_Class_Manager implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance  = NULL;
    
    /**
     * The WOOPS environment object
     */
    private $_env              = NULL;
    
    /**
     * The WOOPS module manager
     */
    private $_modManager       = NULL;
    
    /**
     * Wheter to use AOP classes (generated and stored in the class cache)
     */
    private $_enableAop       = '';
    
    /**
     * The cache directory for the AOP classes
     */
    private $_cacheDirectory   = '';
    
    /**
     * The loaded classes from the WOOPS project
     */
    private $_loadedClasses    = array();
    
    /**
     * The available top WOOPS packages
     */
    private $_packages         = array();
    
    /**
     * The directory which contains the WOOPS classes
     */
    private $_classDir         = '';
    
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
        // Stores the directory containing the WOOPS classes
        $this->_classDir = realpath(
            dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
        ) . DIRECTORY_SEPARATOR;
        
        // Creates a directory iterator in the directory containing this file
        $dirIterator = new DirectoryIterator( $this->_classDir );
        
        // Adds this class to the loaded classes array
        $this->_loadedClasses[ __CLASS__ ]                        = __FILE__;
        
        // Adds the singleton interface to the loaded classes array
        $this->_loadedClasses[ 'Woops_Core_Singleton_Interface' ] = $this->_classDir
                                                                  . 'Core'
                                                                  . DIRECTORY_SEPARATOR
                                                                  . 'Singleton'
                                                                  . DIRECTORY_SEPARATOR
                                                                  . 'Interface.class.php';
        
        // Process each directory
        foreach( $dirIterator as $file ) {
            
            // Checks if the file is a PHP class file
            if( substr( $file, strlen( $file ) - 10 ) === '.class.php' ) {
                
                // Stores the file name, with it's full path
                $this->_packages[ ( string )$file ] = $file->getPathName();
                
                // Process the next file
                continue;
            }
            
            // Checks if the file is a directory
            if( !$file->isDir() ) {
                
                // File - Process the next file
                continue;
            }
            
            // Checks if the directory is hidden
            if( substr( $file, 0, 1 ) === '.' ) {
                
                // Hidden - Process the next file
                continue;
            }
            
            // Stores the directory name, with it's full path
            $this->_packages[ ( string )$file ] = $file->getPathName();
        }
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
    private static function _error( $message )
    {
        print __CLASS__ . ' error: ' . $message . '. The script has been aborted.';
        exit();
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Core_ClassManager The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance                    = new self();
            
            // Gets the instance of the WOOPS environment
            self::$_instance->_env              = Woops_Core_Env_Getter::getInstance();
            
            // Gets the instance of the WOOPS module manager
            self::$_instance->_modManager       = Woops_Core_Module_Manager::getInstance();
            
            // Gets the class cache deny pattern
            self::$_instance->_enableAop        = Woops_Core_Config_Getter::getInstance()->getVar( 'aop', 'enable' );
            
            // Gets the class cache directory
            self::$_instance->_cacheDirectory   = self::$_instance->_env->getPath( 'cache/classes/' );
            
            // Checks if the cache directory exist
            if( !self::$_instance->_cacheDirectory || !is_dir( self::$_instance->_cacheDirectory ) ) {
                
                // The cache directory does not exist
                self::_error( 'The cache directory for the WOOPS classes does not exist' );
            }
            
            // Checks if the cache directory is writeable
            if( !is_writeable( self::$_instance->_cacheDirectory ) ) {
                
                // The cache directory does not exist
                self::_error( 'The cache directory for the WOOPS classes is not writeable' );
            }
            
            // Adds the WOOPS version to the HTTP headers
            header( 'X-WOOPS-VERSION: ' . Woops_Core_Informations::WOOPS_VERSION );
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * SPL autoload method
     * 
     * When registered with the spl_autoload_register() function, this method
     * will be called each time a class cannot be found, and will try to
     * load it.
     * 
     * @param   string  The name of the class to load
     * @return  boolean
     * @see     getInstance
     * @see     _loadClass
     */
    public static function autoLoad( $className )
    {
        // Instance of this class
        static $instance = NULL;
        
        // Checks if the instance of the class has already been fetched
        if( !is_object( $instance ) ) {
            
            // Gets the instance of this class
            $instance = self::getInstance();
        }
        
        // Checks if the class belongs to the 'Woops' package
        if( substr( $className, 0, 6 ) === 'Woops_' ) {
            
            // Gets the class root package
            $rootPkg = substr( $className, 6, strpos( $className, '_', 6 ) - 6 );
            
            // Checks if the requested class belongs to the WOOPS cources, or from a WOOPS module
            if( isset( $instance->_packages[ $rootPkg ] )
                || isset( $instance->_packages[ $className . '.class.php' ] )
            ) {
                
                // Loads the class
                return $instance->_loadClass( $className );
                
            } elseif( $rootPkg == 'Mod' ) {
                
                // Loads the class
                return $instance->_loadClass( $className, true );
            }
        }
        
        // The requested class does not belong to this project
        return false;
    }
    
    /**
     * Loads a class from this project
     * 
     * @param   string  The name of the class to load
     * @param   boolean Wheter the requested class belongs to a WOOPS module
     * @return  boolean
     */
    private function _loadClass( $className, $moduleClass = false )
    {
        // Checks if we are loading a module class or not
        if( $moduleClass ) {
            
            $modName   = substr( $className, 10, strpos( $className, '_', 10 ) - 10 );
            $modPath   = $this->_modManager->getModulePath( $modName );
            $classPath = $modPath
                       . 'classes'
                       . DIRECTORY_SEPARATOR
                       . str_replace( '_', DIRECTORY_SEPARATOR, substr( $className, strlen( $modName ) + 11 ) )
                       . '.class.php';
            
        } else {
            
            // Gets the class path
            $classPath = $this->_classDir
                       . str_replace( '_', DIRECTORY_SEPARATOR, substr( $className, 6 ) )
                       . '.class.php';
        }
        
        // Checks if the class file exists
        if( file_exists( $classPath ) ) {
            
            // Checks if we must use a cached version or not (cache is disabled for the classes form the 'Core' package)
            if( defined( 'WOOPS_AOP_MODE_OFF' )
                || substr( $className, 0, 11 ) === 'Woops_Core_'
                || substr( $className, -9 ) === 'Interface'
                || !$this->_enableAop
            ) {
                
                // Includes the original class file
                require_once( $classPath );
                
            } else {
                
                // Path to the cached version
                $cachedClassPath = $this->_cacheDirectory . $className . '.class.php';
                
                // Checks if the cached version exists
                if( !file_exists( $cachedClassPath ) ) {
                    
                    // Creates the cached version
                    $this->_createCachedClass( $className, $classPath );
                }
                
                // Includes the cached version
                require_once( $cachedClassPath );
            }
            
            // Checks if the requested class is an interface
            if( substr( $className, -9 ) === 'Interface' ) {
                
                // Checks if the interface is defined
                if( !interface_exists( $className ) ) {
                    
                    // Error message
                    $errorMsg = 'The interface '
                              . $className
                              . ' is not defined in file '
                              . $classPath;
                    
                    // The class is not defined
                    self::_error( $errorMsg );
                }
                
            } else {
                    
                // Checks if the class is defined
                if( !class_exists( $className ) ) {
                    
                    // Error message
                    $errorMsg = 'The class '
                              . $className
                              . ' is not defined in file '
                              . $classPath;
                    
                    // The class is not defined
                    self::_error( $errorMsg );
                }
                
                // Checks if the PHP_COMPATIBLE constant is defined
                if( !defined( $className . '::PHP_COMPATIBLE' ) ) {
                    
                    // Error message
                    $errorMsg = 'The requested constant PHP_COMPATIBLE is not defined in class '
                              . $className;
                    
                    // Class does not respect the project conventions
                    self::_error( $errorMsg );
                }
                
                // Gets the minimal PHP version required (eval() is required as late static bindings are implemented only in PHP 5.3)
                eval( '$phpCompatible = ' . $className . '::PHP_COMPATIBLE;' );
                
                // Checks the PHP version
                if( version_compare( PHP_VERSION, $phpCompatible, '<' ) ) {
                    
                    // Error message
                    $errorMsg = 'Class '
                              . $className
                              . ' requires PHP version '
                              . $phpCompatible
                              . ' (actual version is '
                              . PHP_VERSION
                              . ')';
                    
                    // PHP version is too old
                    self::_error( $errorMsg );
                }
            }
            
            // Adds the class to the loaded classes array
            $this->_loadedClasses[ $className ] = $classPath;
            
            // Class was successfully loaded
            return true;
        }
        
        // Class file was not found
        return false;
    }
    
    /**
     * 
     */
    private function _createCachedClass( $className, $classPath )
    {
        // Gets the host informations
        $host     = $this->_env->HTTP_HOST;
        $port     = $this->_env->SERVER_PORT;
        $ssl      = ( boolean )$this->_env->HTTPS;
        $protocol = $this->_env->SERVER_PROTOCOL;
        
        // Gets the URL to the cache building script
        $url      = $this->_env->getSourceWebPath( 'scripts/build-class-cache.php' );
        
        // Checks if we are running an SSL connection
        $host     = ( $ssl ) ? 'ssl://' . $host : $host;
        
        // Checks if the protocol is defined
        $protocol = ( $protocol ) ? $protocol : 'HTTP/1.1';
        
        // Query string for the build script
        $query    = 'woops[aop][buildClass]=' . urlencode( $className );
        
        // Error containers
        $errno    = 0;
        $errstr   = '';
        
        // Creates a socket
        $sock     = fsockopen( $host, $port, $errno, $errstr );
        
        if( !$sock ) {
            
            // Error message
            $errorMsg = 'Error creating a socket for '
                      . $host
                      . ':'
                      . $port
                      . ' ('
                      . $errstr
                      . '). This is required to build the cached version of class '
                      . $className;
            
            // The cache version was not built
            self::_error( $errorMsg );
        }
        
        // New line character
        $nl = chr( 13 ) . chr( 10 );
        
        // Connection for the socket
        $req = 'GET ' . $url . '?' . $query . ' ' . $protocol . $nl
             . 'Host: ' . $host . $nl
             . 'Connection: Close' . $nl . $nl;
        
        // Connects to the build script
        fwrite( $sock, $req );
        
        // Gets the connection status
        $status = fgets( $sock, 128 );
        
        // Checks the status
        if( !$status || substr( $status, -4, 2 ) !== 'OK' ) {
            
            // Error message
            $errorMsg = 'Error connecting to '
                      . $host
                      . ':'
                      . $port
                      . $url
                      . '. This is required to build the cached version of class '
                      . $className;
            
            // Problem connecting to the build script
            self::_error( $errorMsg );
        }
        
        // Build state
        $buildState = 'ERROR';
        
        // Reads the response
        while( !feof( $sock ) ) {
            
            // Gets a line
            $line = fgets( $sock, 128 );
            
            // Checks for the end of the headers
            if( $line === $nl ) {
                
                // No need to contine reading the response
                break;
            }
            
            // Checks for the AOP build status header
            if( substr( $line, 0, 25 ) === 'X-WOOPS-AOP-BUILD-STATUS:' ) {
                
                // Sets the build state
                $buildState = substr( $line, 26, -2 );
                
                // No need to contine reading the response
                break;
            }
        }
        
        // Closes the socket
        fclose( $sock );
        
        // Checks the build state
        if( $buildState !== 'OK' ) {
            
            // Error message
            $errorMsg = 'Error trying to build the cached version of class '
                      . $className;
            
            // The cache version was not built
            self::_error( $errorMsg );
        }
    }
    
    /**
     * Gets the loaded classes from the WOOPS project
     * 
     * @return  array   An array with the loaded classes
     */
    public function getLoadedClasses()
    {
        // Returns the loaded classes from the WOOPS project
        return $this->_loadedClasses;
    }
}

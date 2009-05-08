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

// Includes the WOOPS singleton interface as well as the informations and object
// classes.
// The WOOPS class manager can't auto-load them, since it's not available yet.
// So they have to be included manually. This should be the last time we use
// the require_once() function inside WOOPS.
require_once( realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Singleton' ) . DIRECTORY_SEPARATOR . 'Interface.class.php' );
require_once( realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' ) . DIRECTORY_SEPARATOR . 'Informations.class.php' );
require_once( realpath( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' ) . DIRECTORY_SEPARATOR . 'Object.class.php' );

/**
 * WOOPS class manager
 * 
 * This class will handle every request to a class from the WOOPS project,
 * by automatically loading the class file (thanx to the SPL).
 * 
 * If an error occurs during the load process, this class will simply prints
 * the error message.
 * No trigger_error, nor exception, as this may cause strange PHP behavior,
 * because of the particularity of the SPL autoload method.
 * So no fancy error reporting in such a case, unfortunately.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Class
 */
final class Woops_Core_Class_Manager extends Woops_Core_Object implements Woops_Core_Singleton_Interface
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
     * The WOOPS environment object
     */
    private $_env             = NULL;
    
    /**
     * The WOOPS module manager
     */
    private $_modManager      = NULL;
    
    /**
     * Whether to enable the class cache
     */
    private $_classCache      = false;
    
    /**
     * Whether to use AOP classes (if true, the class cache will be automatically enabled)
     */
    private $_enableAop       = false;
    
    /**
     * The cache directory for the AOP classes
     */
    private $_cacheDirectory  = '';
    
    /**
     * The loaded classes from the WOOPS project
     */
    private $_loadedClasses   = array();
    
    /**
     * The available top WOOPS packages
     */
    private $_packages        = array();
    
    /**
     * The directory which contains the WOOPS classes
     */
    private $_classDir        = '';
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return void
     */
    private function __construct()
    {
        // Checks the PHP version required to run this class
        if( version_compare( PHP_VERSION, self::PHP_COMPATIBLE, '<' ) ) {
            
            // Error message
            $errorMsg = 'Class '
                      . __CLASS__
                      . ' requires PHP version '
                      . self::PHP_COMPATIBLE
                      . ' (actual version is '
                      . PHP_VERSION
                      . ')';
            
            // PHP version is too old
            self::_error( $errorMsg );
        }
        
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
     * Creates an error message
     * 
     * This function will abort the current script, and writes the passed
     * error message.
     * As we are using the SPL autoload functionnalities, we cannot use
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
     * @return  Woops_Core_ClassManager The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
            
            // Loads the error and exception handler (otherwise a problem here won't be able to be reported)
            self::$_instance->_loadClass( 'Woops_Core_Error_Handler' );
            self::$_instance->_loadClass( 'Woops_Core_Exception_Handler' );
            
            // Gets the instance of the WOOPS environment
            self::$_instance->_env              = Woops_Core_Env_Getter::getInstance();
            
            // Gets the instance of the WOOPS module manager
            self::$_instance->_modManager       = Woops_Core_Module_Manager::getInstance();
            
            // Checks if we must use AOP classes
            self::$_instance->_enableAop        = Woops_Core_Config_Getter::getInstance()->getVar( 'aop', 'enable' );
            
            // If AOP is enabled, the class cache must also be enabled
            if( self::$_instance->_enableAop ) {
                
                // Enables the class cache
                self::$_instance->_classCache = true;
                
            } else {
                
                // Checks if we must use a class cache
                self::$_instance->_classCache = Woops_Core_Config_Getter::getInstance()->getVar( 'classCache', 'enable' );
            }
            
            // Checks if we must use cached classes
            if( self::$_instance->_classCache ) {
                
                // Gets the class cache directory
                self::$_instance->_cacheDirectory = self::$_instance->_env->getPath( 'cache/classes/' );
                
                // Checks if the cache directory exist, and is writeable
                if( !self::$_instance->_cacheDirectory
                    || !is_dir( self::$_instance->_cacheDirectory )
                    || !is_writeable( self::$_instance->_cacheDirectory )
                ) {
                    
                    // Disables the AOP and the class cache
                    // Maybe this should generate a fatal error, but in that
                    // case, and if we are installing WOOPS, this could
                    // generate a bad first impression...
                    self::$_instance->_classCache = false;
                    self::$_instance->_enableAop  = false;
                    define( 'WOOPS_CLASS_CACHE_MODE_OFF', true );
                }
            }
            
            // Adds the WOOPS version to the HTTP headers
            header( 'X-WOOPS-VERSION: ' . Woops_Core_Informations::WOOPS_VERSION . '-' . Woops_Core_Informations::WOOPS_VERSION_SUFFIX );
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
     * Loads a class from the WOOPS project
     * 
     * This method will try to load a WOOPS class, either from the sources or
     * from a module's classes directory, depending on the prefix.
     * It will also checks for the PHP_COMPATIBLE constant, inside the class.
     * If it does not exists, of if it's value is bigger than the current PHP
     * version, the script will be aborted, and an error displayed.
     * If the AOP settings are turned on, this method will also create an AOP
     * cached version of the class, and load it from here (if it exists
     * already in the cache, it will load it directly from there).
     * 
     * @param   string  The name of the class to load
     * @param   boolean Whether the requested class belongs to a WOOPS module
     * @return  boolean
     * @see     _createCachedClass
     */
    private function _loadClass( $className, $moduleClass = false )
    {
        // Path to the cached version of the class
        $cachedClassPath = $this->_cacheDirectory . $className . '.class.php';
                
        // Checks if the cache is enabled and if the class exists in the cache
        if( $this->_classCache && file_exists( $cachedClassPath ) && !defined( 'WOOPS_CLASS_CACHE_MODE_OFF' ) ) {
            
            // Includes the cached version
            require_once( $cachedClassPath );
            
            // Sets the class path
            $classPath = $cachedClassPath;
            
        } else {
            
            // Checks if we are loading a module class or not
            if( $moduleClass ) {
                
                // Gets the path to the module class
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
                
                // Checks if we must use a cached version or not
                if( !$this->_classCache || defined( 'WOOPS_CLASS_CACHE_MODE_OFF' ) ) {
                    
                    // Includes the original class file
                    require_once( $classPath );
                    
                } else {
                    
                    // Creates the cached version
                    $this->_createCachedClass( $className );
                    
                    // Includes the cached version
                    require_once( $cachedClassPath );
                }
            } else {
                
                // Class file was not found
                return false;
            }
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
    
    /**
     * Creates an AOP cached version of a PHP class
     * 
     * This method will creates a socket connection to the
     * 'woops-src/scripts/build-class-cache.php' script, that will build the
     * cached version.
     * As the build script needs a reflection object to know if the class has
     * AOP features, we cannot build the cached version from here, as it will
     * mean loading the original class and then the cached one ('Cannot
     * redeclare class XXX', you know the drill), hence the socket connection.
     * 
     * @param   string  The name of the class for which to build a cached version
     * @return  void
     */
    private function _createCachedClass( $className )
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
        $query    = 'woops[classCache][className]=' . urlencode( $className );
        
        // Error containers
        $errno    = 0;
        $errstr   = '';
        
        // Creates a socket
        $sock     = fsockopen( $host, $port, $errno, $errstr );
        
        // Checks if the socket is active
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
            if( substr( $line, 0, 33 ) === 'X-WOOPS-CLASS-CACHE-BUILD-STATUS:' ) {
                
                // Sets the build state
                $buildState = substr( $line, 34, -2 );
                
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
    
    /**
     * Gets the instance of a singleton class
     * 
     * @param   string  The name of the class
     * @return  object  The instance of the class
     */
    public function getSingleton( $className )
    {
        // Creates a reflection object for the requested class
        $reflection = Woops_Core_Reflection_Class::getInstance( $className );
        
        // Checks if the class is a singleton
        if( $reflection->isSingleton() ) {
            
            // Returns the singleton instance
            return $reflection->getMethod( 'getInstance' )->invoke( array() );
            
        } else {
            
            // Error, the class is not a singleton
            throw new Woops_Core_Class_Manager_Exception(
                'The class \'' . $className . '\' is not a singleton',
                Woops_Core_Class_Manager_Exception::EXCEPTION_NOT_SINGLETON
            );
        }
    }
    
    /**
     * Gets an instance of a multi-singleton class
     * 
     * @param   string  The name of the class
     * @param   string  The name of the instance
     * @return  object  The instance of the class
     */
    public function getMultiSingleton( $className, $instanceName )
    {
        // Creates a reflection object for the requested class
        $reflection = Woops_Core_Reflection_Class::getInstance( $className );
        
        // Checks if the class is a multi-singleton
        if( $reflection->isMultiSingleton() ) {
            
            // Returns the singleton instance
            return $reflection->getMethod( 'getInstance' )->invoke( array( $instanceName ) );
            
        } else {
            
            // Error, the class is not a multi-singleton
            throw new Woops_Core_Class_Manager_Exception(
                'The class \'' . $className . '\' is not a multi-singleton',
                Woops_Core_Class_Manager_Exception::EXCEPTION_NOT_MULTISINGLETON
            );
        }
    }
}

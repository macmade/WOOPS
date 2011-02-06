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
namespace Woops\Core\Env;

/**
 * WOOPS environment class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Env
 */
class Getter extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The name of the WOOPS source directory
     */
    const WOOPS_SOURCE_DIRNAME = 'woops-src';
    
    /**
     * The WOOPS module manager
     */
    protected $_modManager      = NULL;
    
    /**
     * An array with references to $_SERVER and $_ENV
     */
    protected $_envVars         = array
    (
        '_SERVER' => array(),
        '_ENV'    => array()
    );
    
    /**
     * The processed server variables (for $_SERVER and $_ENV )
     */
    protected $_serverVars      = array();
    
    /**
     * The WOOPS variables
     */
    protected $_woopsVars       = array
    (
        'sys' => array
        (
            'root' => '',
            'src'  => ''
        ),
        'web' => array
        (
            'root' => '',
            'src'  => ''
        )
    );
    
    /**
     * The loaded WOOPS modules
     */
    protected $_loadedModules   = array();
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return void
     */
    protected function __construct()
    {
        // Stores references to the environment vars
        $this->_envVars[ '_SERVER' ]  = &$_SERVER;
        $this->_envVars[ '_ENV' ]     = &$_ENV;
        
        // Gets the directories for the script name and the script file name
        $scriptFileDir                = dirname( $this->getVar( 'SCRIPT_FILENAME' ) );
        $scriptDir                    = dirname( $this->getVar( 'SCRIPT_NAME' ) );
        
        // Gets the real path of the current script (we may have symbolic links)
        $realScriptFileDir            = realpath( $scriptFileDir );
        
        // Gets the absolute path to the WOOPS sources (here we are in classes/Woops/Core/Env/)
        $sourceAbsPath = realpath
        (
            __DIR__
          . DIRECTORY_SEPARATOR
          . '..'
          . DIRECTORY_SEPARATOR
          . '..'
          . DIRECTORY_SEPARATOR
          . '..'
          . DIRECTORY_SEPARATOR
          . '..'
        ) . DIRECTORY_SEPARATOR;
        
        // Checks if the current script is inside the WOOPS sources
        if( strpos( $realScriptFileDir, $sourceAbsPath ) === 0 )
        {
            $offset = strlen( self::WOOPS_SOURCE_DIRNAME . DIRECTORY_SEPARATOR . str_replace( $sourceAbsPath, '', $realScriptFileDir ) );
            
            // The WOOPS root is in the parent directory
            $this->_woopsVars[ 'sys' ][ 'root' ] = substr( $scriptFileDir, 0, -$offset );
            $this->_woopsVars[ 'web' ][ 'root' ] = substr( $scriptDir, 0, -$offset );
        }
        else
        {
            // The WOOPS root is in the same directory
            $this->_woopsVars[ 'sys' ][ 'root' ] = $scriptFileDir . DIRECTORY_SEPARATOR;
            $this->_woopsVars[ 'web' ][ 'root' ] = str_replace( DIRECTORY_SEPARATOR, '/', $scriptDir );
        }
        
        // Sets the path to the WOOPS sources (we are not using the path computed before, in order to deal with symbolic links)
        $this->_woopsVars[ 'sys' ][ 'src' ]  = $this->_woopsVars[ 'sys' ][ 'root' ] . self::WOOPS_SOURCE_DIRNAME . DIRECTORY_SEPARATOR;
        
        // Adds a trailing slash to the relative path of the WOOPS root (this may be needed if we are using user home dirs)
        if( substr( $this->_woopsVars[ 'web' ][ 'root' ], -1 ) !== '/' )
        {
            // Adds the trailing slash
            $this->_woopsVars[ 'web' ][ 'root' ] .= '/';
        }
        
        // Sets the relative path to the WOOPS sources
        $this->_woopsVars[ 'web' ][ 'src' ]  = $this->_woopsVars[ 'web' ][ 'root' ] . self::WOOPS_SOURCE_DIRNAME . '/';
        
        // Gets the path to the WOOPS configuration file
        // We are not using the Woops\Core\Config\Getter class as this will result in an endless loop between the constructors
        $ini = $this->getPath( 'config/woops.ini.php' ) ?: $this->getSourcePath( 'config.ini.php' );
        
        // Parses the INI file
        $conf = parse_ini_file( $ini, true );
        
        // Checks if we have loaded modules
        if( isset( $conf[ 'modules' ][ 'loaded' ] ) )
        {
            // Process each loaded modules
            foreach( $conf[ 'modules' ][ 'loaded' ] as $module )
            {
                // Stores the paths of the current module
                // We are not using the Woops\Core\Module\Manager class as this will result in an endless loop between the constructors
                $this->_loadedModules[ $module ] = array
                (
                    'sys' => $this->getPath( 'modules/' . $module )    ?: $this->getSourcePath( 'modules/' . $module ),
                    'web' => $this->getWebPath( 'modules/' . $module ) ?: $this->getSourceWebPath( 'modules/' . $module )
                );
            }
        }
    }
    
    /**
     * Get a server variable.
     * 
     * @param   string  The server variable to get
     * @return  string  The server variable, if available
     * @see     getVar
     */
    public function __get( $name )
    {
        return $this->getVar( $name );
    }
    
    /**
     * Register an server variable.
     *
     * This method is used to register a server variable (either $_SERVER or $_ENV).
     * Valid server variables will be stored in the _serverVars property.
     * 
     * @param   string  The server variable to register
     * @return  boolean
     */
    private function _setServerVar( $var, $lookup )
    {
        $result = false;
        
        switch ( $var )
        {
            case 'PHP_SAPI_NAME':
                
                $this->_serverVars[ $var ] = php_sapi_name();
                $result                    = true;
                break;
            
            case 'SCRIPT_NAME':
                
                $sapi = $this->getVar( 'PHP_SAPI_NAME' );
                
                if( $this->getVar( 'ORIG_PATH_INFO' ) )
                {
                    $this->_serverVars[ 'ORIG_PATH_INFO' ];
                }
                else
                {
                    $this->getVar( 'PATH_INFO' );
                }
                
                // Check SAPI
                if( ( $sapi === 'cgi' || $sapi === 'cgi-fcgi' ) && $pathInfo )
                {
                    $scriptName = $pathInfo;
                }
                elseif( $this->getVar( 'ORIG_SCRIPT_NAME' ) )
                {
                    $scriptName = $this->_serverVars[ 'ORIG_SCRIPT_NAME' ];
                }
                elseif( isset( $this->_envVars[ $lookup ][ 'SCRIPT_NAME' ] ) )
                {
                    $scriptName = $this->_envVars[ $lookup ][ 'SCRIPT_NAME' ];
                }
                else
                {
                    $scriptName = NULL;
                }
                
                // Removes double slashes (could happen with PHP CGI under Apache on Windows)
                $scriptName = str_replace( '//', '/', $scriptName );
                
                // Store variable
                $this->_serverVars[ $var ] = $scriptName;
                $result                    = true;
                break;
            
            case 'SCRIPT_FILENAME':
                
                $sapi = $this->getVar( 'PHP_SAPI_NAME' );
                
                if( $this->getVar( 'ORIG_PATH_TRANSLATED' ) )
                {
                    $pathTranslated = $this->_serverVars[ 'ORIG_PATH_TRANSLATED' ];
                }
                else
                {
                    $pathTranslated = $this->getVar( 'PATH_TRANSLATED' );
                }
                
                // Check SAPI
                if( ( $sapi === 'cgi' || $sapi === 'cgi-fcgi' || $sapi === 'isapi' ) && $pathTranslated )
                {
                    $scriptFileName = $pathTranslated;
                }
                elseif( $this->getVar( 'ORIG_SCRIPT_FILENAME' ) )
                {
                    $scriptFileName = $this->_serverVars[ 'ORIG_SCRIPT_FILENAME' ];
                }
                elseif( isset( $this->_envVars[ $lookup ][ 'SCRIPT_FILENAME' ] ) )
                {
                    $scriptFileName = $this->_envVars[ $lookup ][ 'SCRIPT_FILENAME' ];
                }
                else
                {
                    $scriptFileName = NULL;
                }
                
                // Removes double slashes (could happen with PHP CGI under Apache on Windows)
                $scriptFileName = str_replace( '//', '/', $scriptFileName );
                
                // Store variable
                $this->_serverVars[ $var ] = $scriptFileName;
                $result                    = true;
                break;
            
            case 'HTTPS':
                
                $this->_serverVars[ $var ] = ( isset( $this->_envVars[ $lookup ][ $var ] ) && $this->_envVars[ $lookup ][ $var ] != 'off' ) ? true : false;
                break;
                
            case 'REQUEST_URI':
                
                if( isset( $this->_envVars[ $lookup ][ 'REQUEST_URI' ] ) )
                {
                    $requestUri = $this->_envVars[ $lookup ][ 'REQUEST_URI' ];
                }
                elseif( $this->getVar( 'SCRIPT_NAME' ) )
                {
                    if( $this->getVar( 'QUERY_STRING' ) )
                    {
                        $requestUri  = $this->_serverVars[ 'SCRIPT_NAME' ];
                        $requestUri .= '?' . $this->_serverVars[ 'QUERY_STRING' ];
                    }
                    else
                    {
                        $requestUri = $this->_serverVars[ 'SCRIPT_NAME' ];
                    }
                }
                else
                {
                    $requestUri = NULL;
                }
                
                // Store variable
                $this->_serverVars[ $var ] = $requestUri;
                $result                    = true;
                break;
            
            case 'DOCUMENT_ROOT':
                
                $scriptName                = $this->getVar( 'SCRIPT_NAME' );
                $scriptFileName            = $this->getVar( 'SCRIPT_FILENAME' );
                
                if( substr( $scriptName, 0, 2 ) === '/~' )
                {
                    $secondSlashPos            = strpos( $scriptName, '/', 1 );
                    $webPart                   = substr( $scriptName, $secondSlashPos );
                    $this->_serverVars[ $var ] = str_replace( str_replace( '/', DIRECTORY_SEPARATOR, $webPart ), '', $scriptFileName );
                }
                else
                {
                    $this->_serverVars[ $var ] = str_replace( str_replace( '/', DIRECTORY_SEPARATOR, $scriptName ), '', $scriptFileName );
                }
                
                $result = true;
                break;
            
            case 'PHP_SELF':
                
                // Check for PHP_SELF variable in ${ $lookup }
                if( isset( $this->_envVars[ $lookup ][ $var ] ) )
                {
                    // Removes double slashes (could happen with PHP CGI under Apache on Windows)
                    $phpSelf = str_replace( '//', '/', $this->_envVars[ $lookup ][ $var ] );
                    
                    $this->_serverVars[ $var ] = $phpSelf;
                    $result                    = true;
                }
                break;
                
            // Default processing
            default:
                
                // Check for requested variable in ${ $lookup }
                if( isset( $this->_envVars[ $lookup ][ $var ] ) )
                {
                    $this->_serverVars[ $var ] = $this->_envVars[ $lookup ][ $var ];
                    $result                    = true;
                }
                break;
        }
        
        return $result;
    }
    
    /**
     * 
     */
    public function getPath( $path )
    {
        if( substr( $path, 0, 12 ) === 'woops-mod://' )
        {
            $moduleName = substr( $path, 12, strpos( $path, '/', 12 ) - 12 );
            
            if( !isset( $this->_loadedModules[ $moduleName ] ) )
            {
                return false;
            }
            
            $modPath = $this->_loadedModules[ $moduleName ][ 'sys' ] . DIRECTORY_SEPARATOR;
            $absPath = $modPath . str_replace( '/', DIRECTORY_SEPARATOR, substr( $path, 13 + strlen( $moduleName ) ) );
            
            return ( file_exists( $absPath ) ) ? $absPath : false;
        }
        else
        {
            $absPath = $this->_woopsVars[ 'sys' ][ 'root' ]
                     . str_replace( '/', DIRECTORY_SEPARATOR, $path );
            
            return ( file_exists( $absPath ) ) ? $absPath : false;
        }
    }
    
    /**
     * 
     */
    public function getSourcePath( $path )
    {
        $absPath = $this->_woopsVars[ 'sys' ][ 'src' ]
                 . str_replace( '/', DIRECTORY_SEPARATOR, $path );
        
        return ( file_exists( $absPath ) ) ? $absPath : false;
    }
    
    /**
     * 
     */
    public function getWebPath( $path )
    {
        if( !$this->getPath( $path ) )
        {
            return false;
        }
        
        if( substr( $path, 0, 12 ) === 'woops-mod://' )
        {
            $moduleName = substr( $path, 12, strpos( $path, '/', 12 ) - 12 );
            
            if( !isset( $this->_loadedModules[ $moduleName ] ) )
            {
                return false;
            }
            
            $modPath = $this->_loadedModules[ $moduleName ][ 'web' ] . '/';
            $webPath = $modPath . substr( $path, 13 + strlen( $moduleName ) );
        }
        else
        {
            $webPath = $this->_woopsVars[ 'web' ][ 'root' ] . $path;
        }
        
        return $webPath;
    }
    
    /**
     * 
     */
    public function getSourceWebPath( $path )
    {
        $webPath = $this->_woopsVars[ 'web' ][ 'src' ] . $path;
        $absPath = $this->getSourcePath( $path );
        
        return ( file_exists( $absPath ) ) ? $webPath : false;
    }
    
    /**
     * Get a server variable.
     *
     * This method is used to get a server variable (either $_SERVER or $_ENV),
     * If the variable is not already registered, it will
     * first set it using method _setServerVar.
     * Priority is given to $_SERVER, then $_ENV.
     * 
     * @param   string  The server variable to get
     * @return  string  The server variable, if available
     * @see     _setServerVar
     */
    public function getVar( $var )
    {
        // Ensures we have a string
        $var = ( string )$var;
        
        // Checks if the varaible exists, or if it has to be processed
        if( isset( $this->_serverVars[ $var ] ) )
        {
            // Returns the existing variable
            return $this->_serverVars[ $var ];
        }
        elseif( $this->_setServerVar( $var, '_SERVER' ) )
        {
            // Looks in $_SERVER
            return $this->_serverVars[ $var ];
        }
        elseif( $this->_setServerVar( $var, '_ENV' ) )
        {
            // Looks in $_ENV
            return $this->_serverVars[ $var ];
        }
        
        // No such variable
        return false;
    }
}

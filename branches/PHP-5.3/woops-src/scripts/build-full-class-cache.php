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

// As we are building cached version of classes, we don't want the WOOPS class
// manager to load classes from the cache, as this will result in an
// infinite number of calls to this script, through a socket. 
define( 'WOOPS_CLASS_CACHE_MODE_OFF', true );

// Includes the initialization script
require_once(
    __DIR__
  . DIRECTORY_SEPARATOR
  . '..'
  . DIRECTORY_SEPARATOR
  . 'init.inc.php'
);

// File encoding
declare( ENCODING = 'UTF-8' );

// Gets the environment object
$ENV = Woops\Core\Env\Getter::getInstance();

// Creates a recursive directory iterator
$ITERATOR = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        $ENV->getSourcePath( 'classes/Woops/' )
    )
);

// Process each element of the iterator
foreach( $ITERATOR as $FILEPATH => $FILE ) {
    
    // Checks if the file is a class
    if( $FILE->isFile() && substr( $FILEPATH, -10 ) === '.class.php' ) {
        
        // Gets the relative class path
        $CLASS_RELPATH = str_replace( $ENV->getSourcePath( 'classes/' ), '', $FILEPATH );
        
        // Gets the class name
        $CLASSNAME     = str_replace( DIRECTORY_SEPARATOR, '\\', substr( $CLASS_RELPATH, 0, -10 ) );
        
        // Builds the cached version
        createClassCache( $CLASSNAME );
    }
}

// Gets the module manager
$MOD_MANAGER = Woops\Core\Module\Manager::getInstance();

// Gets the available modules
$MODULES = $MOD_MANAGER->getAvailableModules();

// Process each module
foreach( $MODULES as $MODNAME => $MODINFOS ) {
    
    // Checks if the module is loaded
    if( !$MOD_MANAGER->isLoaded( $MODNAME ) ) {
        
        // Do not process not loaded modules
        continue;
    }
    
    // Checks if the module has a class directory
    if( file_exists( $MODINFOS[ 0 ] . 'classes' ) && is_dir( $MODINFOS[ 0 ] . 'classes' ) ) {
        
        // Creates a recursive directory iterator
        $ITERATOR = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $MODINFOS[ 0 ] . 'classes/'
            )
        );
        
        // Process each element of the iterator
        foreach( $ITERATOR as $FILEPATH => $FILE ) {
            
            // Checks if the file is a class
            if( $FILE->isFile() && substr( $FILEPATH, -10 ) === '.class.php' ) {
                
                // Gets the relative class path
                $CLASS_RELPATH = str_replace( $MODINFOS[ 0 ] . 'classes/', '', $FILEPATH );
                
                // Gets the class name
                $CLASSNAME     = 'Woops\Mod\\' . $MODNAME . '\\' . str_replace( DIRECTORY_SEPARATOR, '\\', substr( $CLASS_RELPATH, 0, -10 ) );
                
                // Builds the cached version
                createClassCache( $CLASSNAME );
            }
        }
    }
}

/**
 * Creates the cached version of a class
 * 
 * @param   string  The name of the class for which to build a cached version
 * @return  void
 */
function createClassCache( $className )
{
    // The environment object
    static $env;
    
    // The configuration object
    static $conf;
    
    // The path to the cache directory for the PHP classes
    static $cacheDir;
    
    // Whether AOP is enabled
    static $aop;
    
    // Whether the PHP code must be optimized
    static $optimize;
    
    // Checks if we already have the environment object
    if( !is_object( $env ) ) {
        
        // Gets the environment object
        $env      = Woops\Core\Env\Getter::getInstance();
        
        // Gets the configuration object
        $conf     = Woops\Core\Config\Getter::getInstance();
        
        // Sets the path to the cache directory
        $cacheDir = $env->getPath( 'cache/classes/' );
        
        // Gets the AOP state
        $aop      = $conf->getVar( 'aop', 'enable' );
        
        // Gets the optimize state
        $optimize = $conf->getVar( 'classCache', 'optimize' );
    }
    
    // Checks if the cached version already exists
    if( file_exists( $cacheDir . str_replace( '\\', '.', $className ) . '.class.php' ) ) {
        
        // Nothing to do, the cached version already exists
        return;
    }
    
    // Checks if AOP is enabled, and if the class is not an interface
    if( $aop && substr( $className, -9 ) !== 'ObjectInterface' ) {
        
        // Creates an AOP version of the class
        $aopBuilder = new Woops\Core\Aop\Class\Builder( $className );
        
        // Gets the code of the AOP version
        $classCode  = ( string )$aopBuilder;
        
    } else {
        
        // Creates a reflection object
        $reflection = Woops\Core\Reflection\ClassReflectorReflectorReflector::getInstance( $className );
        
        // Gets the PHP source code
        $classCode = file_get_contents( $reflection->getFileName() );
    }
    
    // Checks if the source code must be optimized
    if( $optimize ) {
        
        // Creates a source optimizer
        $optimizer = new Woops\Php\Source\Optimizer( $classCode );
        
        // Gets the optimized source code
        $classCode = ( string )$optimizer;
    }
    
    // Writes the class in the cache
    file_put_contents(
        $cacheDir . str_replace( '\\', '.', $className ) . '.class.php',
        $classCode
    );
}

// Aborts the script
exit();

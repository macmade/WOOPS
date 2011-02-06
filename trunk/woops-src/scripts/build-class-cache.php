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
require_once
(
    __DIR__
  . DIRECTORY_SEPARATOR
  . '..'
  . DIRECTORY_SEPARATOR
  . 'init.inc.php'
);

// File encoding
declare( ENCODING = 'UTF-8' );

// Gets incomming GET variables
$GETVARS = Woops\Core\Request\Getter::getInstance()->classCache;

// Checks the GET variables
if( $GETVARS && isset( $GETVARS[ 'className' ] ) )
{
    // Name and path of the class to build
    $CLASSNAME = $GETVARS[ 'className' ];
    
    // File extension for the cached class file
    $CLASSEXT = ( Woops\Core\Config\Getter::getInstance()->getVar( 'aop', 'enable' ) ) ? '.aop.class.php' : '.class.php';
    
    // Path to the cache directory
    $CACHEDIR  = Woops\Core\Env\Getter::getInstance()->getPath( 'cache/classes/' );
    
    // Checks if the class caching is enabled (or the AOP), and checks the cache directory
    if
    (
           Woops\Core\Config\Getter::getInstance()->getVar( 'classCache', 'enable' )
        || Woops\Core\Config\Getter::getInstance()->getVar( 'aop', 'enable' )
        && $CACHEDIR
        && is_dir( $CACHEDIR )
        && is_writeable( $CACHEDIR )
        && !file_exists( $CACHEDIR . str_replace( '\\', '.', $CLASSNAME . $CLASSEXT ) )
    )
    {
        // We don't want any error here
        try
        {
            // Checks if AOP is enabled, and if the class is not an interface
            if
            (
                   Woops\Core\Config\Getter::getInstance()->getVar( 'aop', 'enable' )
                && substr( $CLASSNAME, -15 ) !== 'ObjectInterface'
            )
            {
                // Creates an AOP version of the class
                $AOP       = new Woops\Core\Aop\ClassBuilder( $CLASSNAME );
                
                // Gets the code of the AOP version
                $CLASSCODE = ( string )$AOP;
            }
            else
            {
                // Creates a reflection object for the class
                $REF       = Woops\Core\Reflection::getClassReflector( $CLASSNAME );
                
                // Gets the PHP source code
                $CLASSCODE = file_get_contents( $REF->getFileName() );
            }
            
            // Checks if the source code must be optimized
            if( Woops\Core\Config\Getter::getInstance()->getVar( 'classCache', 'optimize' ) )
            {
                // Creates a source optimizer
                $OPT       = new Woops\Php\Source\Optimizer( $CLASSCODE );
                
                // Gets the optimized source code
                $CLASSCODE = ( string )$OPT;
            }
            
            // Writes the class in the cache
            file_put_contents
            (
                $CACHEDIR . str_replace( '\\', '.', $CLASSNAME ) . $CLASSEXT,
                $CLASSCODE
            );
            
            // The cached version of the class was built
            header( 'X-WOOPS-CLASS-CACHE-BUILD-STATUS: OK' );
            
            // Aborts the script
            exit();
        }
        catch( Exception $e )
        {
            // DEBUG ONLY!!!
            #throw( $e );
        }
    }
}

// The cached version of the class was not built
header( 'X-WOOPS-CLASS-CACHE-BUILD-STATUS: ERROR' );

// Aborts the script
exit();

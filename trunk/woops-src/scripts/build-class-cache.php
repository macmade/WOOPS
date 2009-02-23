<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

// As we are building cached version of classes, we don't want the WOOPS class
// manager to load classes from the cache, as this will result in an
// infinite number of calls to this script, through a socket. 
define( 'WOOPS_CLASS_CACHE_MODE_OFF', true );

// Includes the initialization script
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'init.inc.php' );

// Gets incomming GET variables
$GETVARS = Woops_Core_Request_Getter::getInstance()->classCache;

// Checks the GET variables
if( $GETVARS && isset( $GETVARS[ 'className' ] ) ) {
    
    // Name and path of the class to build
    $CLASSNAME = $GETVARS[ 'className' ];
    
    // Path to the cache directory
    $CACHEDIR  = Woops_Core_Env_Getter::getInstance()->getPath( 'cache/classes/' );
    
    // Checks if the class caching is enabled (or the AOP), and checks the cache directory
    if(    Woops_Core_Config_Getter::getInstance()->getVar( 'classCache', 'enable' )
        || Woops_Core_Config_Getter::getInstance()->getVar( 'aop', 'enable' )
        && $CACHEDIR
        && is_dir( $CACHEDIR )
        && is_writeable( $CACHEDIR )
        && !file_exists( $CACHEDIR . $CLASSNAME )
    ) {
        
        // We don't want any error here
        try {
            
            // Checks if AOP is enabled, and if the class is not an interface
            if( Woops_Core_Config_Getter::getInstance()->getVar( 'aop', 'enable' )
                && substr( $CLASSNAME, -9 ) !== 'Interface'
            ) {
                
                // Creates an AOP version of the class
                $AOP       = new Woops_Core_Aop_Class_Builder( $CLASSNAME );
                
                // Gets the code of the AOP version
                $CLASSCODE = ( string )$AOP;
                
            } else {
                
                // Creates a reflection object for the class
                $REF       = Woops_Core_Reflection_Class::getInstance( $CLASSNAME );
                
                // Gets the PHP source code
                $CLASSCODE = file_get_contents( $REF->getFileName() );
            }
            
            // Checks if the source code must be optimized
            if( Woops_Core_Config_Getter::getInstance()->getVar( 'classCache', 'optimize' ) ) {
                
                // Creates a source optimizer
                $OPT       = new Woops_Php_Source_Optimizer( $CLASSCODE );
                
                // Gets the optimized source code
                $CLASSCODE = ( string )$OPT;
            }
            
            // Writes the class in the cache
            file_put_contents(
                $CACHEDIR . $CLASSNAME . '.class.php',
                $CLASSCODE
            );
            
            // The cached version of the class was built
            header( 'X-WOOPS-CLASS-CACHE-BUILD-STATUS: OK' );
            
            // Aborts the script
            exit();
            
        } catch( Exception $e ) {
            
            // DEBUG ONLY!!!
            #throw( $e );
        }
    }
}

// The cached version of the class was not built
header( 'X-WOOPS-CLASS-CACHE-BUILD-STATUS: ERROR' );

// Aborts the script
exit();

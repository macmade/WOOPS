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

// As we are building cached version of classes, we don't want the WOOPS class
// manager to load classes from the AOP cache, as this will result in an
// infinite number of calls to this script, through a socket.
define( 'WOOPS_AOP_MODE_OFF', true );

// Includes the initialization script
require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'init.inc.php' );

// Gets incomming GET variables
$GETVARS = Woops_Core_Request_Getter::getInstance()->woops;

// Checks the GET variables
if( $GETVARS && isset( $GETVARS[ 'aop' ][ 'buildClass' ] ) ) {
    
    // Name and path of the class to build
    $CLASSNAME = $GETVARS[ 'aop' ][ 'buildClass' ];
    
    // Path to the cache directory
    $CACHEDIR  = Woops_Core_Env_Getter::getInstance()->getPath( 'cache/classes/' );
    
    // Checks the cache directory and the class path
    if( $CACHEDIR
        && is_dir( $CACHEDIR )
        && is_writeable( $CACHEDIR )
        && !file_exists( $CACHEDIR . $CLASSNAME )
    ) {
        
        // We don't want any error here
        try {
            
            // Creates an AOP version of the class
            $AOP = new Woops_Core_Aop_Class_Builder( $CLASSNAME );
            
            // Writes the class in the cache
            file_put_contents(
                $CACHEDIR . $CLASSNAME . '.class.php',
                ( string )$AOP
            );
            
            // The cached version of the class was built
            header( 'X-WOOPS-AOP-BUILD-STATUS: OK' );
            
            // Aborts the script
            exit();
            
        } catch( Exception $e ) {}
    }
}

// The cached version of the class was not built
header( 'X-WOOPS-AOP-BUILD-STATUS: ERROR' );

// Aborts the script
exit();

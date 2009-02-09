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

// Sets the error reporting level to the highest possible value
error_reporting( E_ALL | E_STRICT );

// Checks the PHP version
if( ( double )PHP_VERSION < 5.2 ) {
    
    // We are not running PHP 5.2 or greater
    trigger_error(
        'PHP version 5.2 is required to use this script (actual version is ' . PHP_VERSION . ')',
        E_USER_ERROR
    );
}

// Checks for the SPL
if( !function_exists( 'spl_autoload_register' ) ) {
    
    // The SPL is unavailable
    throw new Exception(
        'The SPL (Standard PHP Library) is required to use this script'
    );
}

// Checks for the SimpleXMLElement class
if( !class_exists( 'SimpleXMLElement' ) ) {
    
    // SimpleXMLElement is unavailable
    throw new Exception(
        'The SimpleXMLElement class is required to use this script'
    );
}

// Includes the Woops class manager
require_once(
    dirname( __FILE__ )
  . DIRECTORY_SEPARATOR
  . 'Classes'
  . DIRECTORY_SEPARATOR
  . 'Woops'
  . DIRECTORY_SEPARATOR
  . 'Core'
  . DIRECTORY_SEPARATOR
  . 'Class'
  . DIRECTORY_SEPARATOR
  . 'Manager.class.php'
);

// Checks the PHP version required to use the WOOPS class manager
if( version_compare( PHP_VERSION, Woops_Core_Class_Manager::PHP_COMPATIBLE, '<' ) ) {
    
    // PHP version is too old
    throw new Exception(
        'The class Woops_Core_Class_Manager requires PHP version ' . Woops_Core_Class_Manager::PHP_COMPATIBLE . ' (actual version is ' . PHP_VERSION . ')'
    );
}

// Registers an SPL autoload method to use to load the classes form the Woops project
spl_autoload_register( array( 'Woops_Core_Class_Manager', 'autoLoad' ) );

// Checks if the WOOPS configuration object is present
if( !isset( $WOOPS_CONF ) || !is_object( $WOOPS_CONF ) ) {
    
    // No configuration
    throw new Exception(
        'The WOOPS configuration object does not exist. The configuration may not be included'
    );
}

// Stores the configuration object
Woops_Core_Config_Getter::setConfiguration( $WOOPS_CONF );

// Cleans-up the global configuration object, as a copy is stored in the WOOPS configuration class
unset( $WOOPS_CONF );

// Sets the error and exception handlers - From now every mistake will produce a fatal error
set_exception_handler( array( 'Woops_Core_Exception_Handler', 'handleException' ) );
set_error_handler(     array( 'Woops_Core_Error_Handler',     'handleError' ) );

// Sets the default timezone
date_default_timezone_set( Woops_Core_Config_Getter::getInstance()->getVar( 'time', 'timezone' ) );
